<?php

namespace Akceli\Console\Commands;

use Akceli\AkceliServiceProvider;
use Akceli\FileService;
use Akceli\Generators\AkceliGenerator;
use Akceli\GeneratorService;
use Akceli\Console;
use Akceli\RemoteGeneratorService;
use Akceli\Schema\SchemaFactory;
use Akceli\TemplateData;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class AkceliGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akceli:generate {template-set?} {arg1?} {arg2?} {arg3?} {arg4?} {arg5?} {arg6?} {arg7?} {arg8?} {arg9?} {arg10?} {--dump} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Akceli https://docs.akceli.io';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        Console::setLogger($this);

        $config = config('akceli');
        $config['generators'] = $config['template-groups'] ?? $config['generators'];

        FileService::setRootDirectory(app_path(config('akceli.model_directory', null)));
        $template_set = $this->argument('template-set');

        /**
         * If config file has not been published then publish it, making sure not to force
         */
        if (is_null($config)) {
            Artisan::call('akceli:publish');
        }

        $templateSets = array_keys($config['generators']);
        if (isset($config['project_key'])) {
            // Merging the remote and local template sets
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'http://local.akceli.io/api/generator-sets/' . $config['project_key']);
            $generators = collect(json_decode($res->getBody()->getContents(), true)['data']);
            $remoteTemplateSets = $generators->pluck('name')->toArray();

            $templateSets = array_merge($templateSets, $remoteTemplateSets);
        }

        /**
         * Selecting a Template
         */
        if (is_null($this->argument('template-set'))) {
            if ($config['select-template-behavior'] ?? 'multiple-choice' === 'auto-complete') {
                $template_set = Console::anticipate('What template set do you want to use? (Press enter to see list of options)', $templateSets);
            } else {
                $template_set = Console::choice('What template set do you want to use?', $templateSets);
            }

            if (is_null($template_set)) {
                $template_set = Console::choice('What template set do you want to use?', $templateSets);
            }
        }
        
        $isRemoteTemplate = false;

        /**
         * Validate the the Template is a valid option
         */
        if (isset($config['generators'][$template_set])) {
            $templateSet = $config['generators'][$template_set];
            if (is_string($templateSet)) {
                $templateSet = new $templateSet();
            }
        } else {
            // This is not a local generator
            if (!in_array($template_set, $remoteTemplateSets)) {
                // This is not a remote generator ether so let the user know.
                Console::error('');
                Console::error('Invalid Template Set: ' . $template_set . ' dose not exist.');
                Console::error('');
                return;
            }

            $isRemoteTemplate = true;
            $templateSet = $generators->first(function ($generator) use ($template_set) {
                return $generator['name'] === $template_set;
            });
        }

        /**
         * Initializing Template Data
         */
        $template_data = [
            'arg1' => $this->argument('arg1'),
            'arg2' => $this->argument('arg2'),
            'arg3' => $this->argument('arg3'),
            'arg4' => $this->argument('arg4'),
            'arg5' => $this->argument('arg5'),
            'arg6' => $this->argument('arg6'),
            'arg7' => $this->argument('arg7'),
            'arg8' => $this->argument('arg8'),
            'arg9' => $this->argument('arg9'),
            'arg10' => $this->argument('arg10'),
            'dump' => $this->option('dump'),
            'force' => $this->option('force'),
        ];

        /**
         * Setup Model Data if Required
         */
        if (true || $templateSet['requires_table_name'] ?? true) {
            $table_name = $this->argument('arg1');

            if (is_null($table_name)) {
                $table_name = $this->ask('What is the table name being used in the template?');
            }
            $model_name = Str::studly(Str::singular($table_name));
            if ($modelFile = FileService::findByTableName($table_name)) {
                $model_name = FileService::getClassNameOfFile($modelFile);
            }

            $schema = SchemaFactory::resolve($table_name);
            $columns = $schema->getColumns();
            $template_data['table_name'] = $table_name;
            $template_data['columns'] = $columns;
            $template_data['primaryKey'] =  ($columns->firstWhere('Key', '==', 'PRI')) ? $columns->firstWhere('Key', '==', 'PRI')->Field : null;
            $template_data = array_merge($template_data, TemplateData::buildModelAliases($model_name));
        } else {
            $columns = collect([]);
        }


        /**
         * Process Data Prompts
         */
        if ($isRemoteTemplate) {
//            dd($templateSet, $templateSet['prompts']);
        } elseif ($templateSet['data'] ?? false) {
            $template_data = Console\DataPrompter\DataPrompter::prompt($templateSet, $template_data, $this->arguments());
        }

        /**
         * Optionaly Dump Template Data
         */
        if ($this->option('dump')) {
            dd($template_data);
        }


        $templateData = new TemplateData($template_data, $columns);
        if ($isRemoteTemplate) {
            try {
                RemoteGeneratorService::setData($template_data);
                RemoteGeneratorService::setFileTemplates($templateSet['templates'] ?? []);
                RemoteGeneratorService::setFileModifiers($templateSet['file_modifiers'] ?? []);

                $generator = new RemoteGeneratorService($templateData);
                $generator->generate($this->option('force'));
            } finally {
                RemoteGeneratorService::removeFileTemplates();
            }
        } else {
            // Is Local Template

            /**
             * This is set similar to a gobal variable for recursion purposes.
             */
            GeneratorService::setData($template_data);
            GeneratorService::setFileTemplates($templateSet['templates'] ?? []);
            GeneratorService::setFileModifiers($templateSet['file_modifiers'] ?? []);
            GeneratorService::setInlineTemplates($templateSet['inline_templates'] ?? []);

            $generator = new GeneratorService($templateData);
            $generator->generate($this->option('force'));

            if ($templateSet['completion_message'] ?? false) {
                $templateSet['completion_message']($template_data);
            }
        }
    }
}
