<?php

namespace Akceli\Console\Commands;

use Akceli\AkceliServiceProvider;
use Akceli\FileService;
use Akceli\GeneratorService;
use Akceli\Console;
use Akceli\Parser;
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
    protected $signature = 'akceli {template-set?} {table-name?} {--dump} {--force} ' .
                                    '{--only-relationships} {--only-templates} ' .
                                    '{--model-name=} {--extra-data=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fully functional CRUD code based on a mysql table instantly';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('    ****************************************');
        $this->info('    *                                      *');
        $this->info('    *                Akceli                *');
        $this->info('    *                                      *');
        $this->info('    ****************************************');

        $template_set = $this->argument('template-set');
        $config = config('akceli');


        /**
         * If config file has not been published then publish it.
         */
        if (is_null($config)) {
            $exitCode = Artisan::call('vendor:publish', [
                '--provider' => AkceliServiceProvider::class
            ]);

            /**
             * Add the Trait to the composer json
             */
            $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);
            $composerJson['autoload-dev'] = $composerJson['autoload-dev'] ?? [];
            $composerJson['autoload-dev']['files'] = $composerJson['autoload-dev']['files'] ?? [];
            array_push($composerJson['autoload-dev']['files'], "resources/akceli/AkceliTableDataTrait.php");
            $newComposerJson = json_encode($composerJson, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
            file_put_contents(base_path('composer.json'), $newComposerJson);


            if ($exitCode) {
                $this->error('');
                $this->error('There was an error publishing the config file: Try running the following command for more details:');
                $this->error('php artisan vendor:publish --provider=' . AkceliServiceProvider::class);
                $this->error('');
                return;
            } else {
                $this->info('The akceli.php config file we published to /config/akceli.php');
                $this->info('resources/akceli/AkceliTableDataTrait.php was published');
                return;
            }
        }

        if (is_null($this->argument('template-set'))) {
            $templateSets = array_keys($config['template-groups']);
            if ($config['select-template-behavior'] ?? 'multiple-choice' === 'auto-complete') {
                $template_set = $this->anticipate('What template set do you want to use? (Press enter to see list of options)', $templateSets);
            } else {
                $template_set = $this->choice('What template set do you want to use?', $templateSets);
            }

            if (is_null($template_set)) {
                $template_set = $this->choice('What template set do you want to use?', $templateSets);
            }
        }

        /**
         * Validate the the Template is a valid option
         */
        if (!isset($config['template-groups'][$template_set])) {
            $this->error('');
            $this->error('Invalid Template Set: ' . $template_set . ' dose not exist in your config file.');
            $this->error('');
            return;
        }

        $templateSet = $config['template-groups'][$template_set];

        $extraData = [
            'app_namespace' => Container::getInstance()->getNamespace()
        ];
        if ($this->option('extra-data')) {
            foreach (explode('|', str_replace('/', '\\', $this->option('extra-data'))) as $set) {
                $parts = explode(':', $set);
                $extraData[$parts[0]] = $parts[1];
            }
        }


        /**
         * Setup Model Data if Required
         */
        if ($templateSet['requires_table_name'] ?? true) {
            $table_name = $this->argument('table-name');
            $model_name = $this->option('model-name');

            if (is_null($table_name)) {
                $table_name = $this->ask('What is the table name being used in the template?');
            }
            $defaultModelName = Str::studly(Str::singular($table_name));
            if (is_null($model_name)) {
                $model_name = $this->ask('What is the Model name for the table?', $defaultModelName);
            }
            if (is_null($model_name)) {
                $model_name = $defaultModelName;
            }

            $schema = SchemaFactory::resolve($table_name);
            $columns = $schema->getColumns();
            $extraData['table_name'] = $table_name;
            $extraData['columns'] = $columns;
            $extraData['primaryKey'] = $columns->firstWhere('Key', '==', 'PRI')->Field;
            $extraData = array_merge($extraData, TemplateData::buildModelAliases($model_name));

            $this::info("Table Name: {$table_name}");
            $this::info("Model Name: {$model_name}");
        } else {
            $columns = collect([]);
        }

        /**
         * Process Data Prompts
         */
        if ($templateSet['data'] ?? false) {
            foreach ($templateSet['data'] as $key => $prompt) {
                $extraData[$key] = $this->{$prompt['type']}($prompt['message']);
            }
        }

        $template_data = array_merge($config['options'], $templateSet['options'] ?? [], $extraData);

        if ($this->option('dump')) {
            dd($template_data);
        }

        /**
         * Setup Global Classes
         */
        Console::setLogger($this);
        FileService::setRootDirectory(base_path(config('akceli.root_model_path')));
        GeneratorService::setData($template_data); // Set Globally for use during relationship generation
        GeneratorService::setFileTemplates($templateSet['templates'] ?? []);
        GeneratorService::setInlineTemplates($templateSet['inline_templates'] ?? []);

        $templateData = new TemplateData($template_data, $columns);
        $generator = new GeneratorService($templateData);

        if ($this->option('only-relationships') && !$this->option('only-templates')) {

            if ($templateSet['requires_table_name'] ?? true) {
//                $classParser = new Parser(base_path('resources/templates/relationship-methods'), 'akceli.php');
//                $classParser->addData($this->templateData->toArray());
//            (new GeneratorFlowController($classParser, $schema, $force))->start();
            } else {
                $this->alert('You can not generate relationships with this template');
            }
            return;
        }

        if ($this->option('only-templates') && !$this->option('only-relationships')) {
            $generator->generate($this->option('force'));
            return;
        }

        $generator->generate($this->option('force'));
        if ($templateSet['requires_table_name'] ?? true) {
//                $classParser = new Parser(base_path('resources/templates/relationship-methods'), 'akceli.php');
//                $classParser->addData($this->templateData->toArray());
//            (new GeneratorFlowController($classParser, $schema, $force))->start();
        }
    }
}
