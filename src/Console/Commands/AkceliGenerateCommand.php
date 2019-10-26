<?php

namespace Akceli\Console\Commands;

use Akceli\AkceliServiceProvider;
use Akceli\GeneratorService;
use Akceli\Console;
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
    protected $signature = 'akceli {template-set?} {table-name?} {--dump} {--force}';

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
        /**
         * Setup Global Classes
         */
        Console::setLogger($this);

        Console::info('    ****************************************');
        Console::info('    *                                      *');
        Console::info('    *                Akceli                *');
        Console::info('    *                                      *');
        Console::info('    ****************************************');

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
            $composerJson['autoload-dev']['psr-4']['Akceli\\Generators\\'] = "akceli/generators/";
            array_push($composerJson['autoload-dev']['files'], "akceli/AkceliTableDataTrait.php");
            array_push($composerJson['autoload-dev']['files'], "akceli/AkceliColumnTrait.php");
            $newComposerJson = json_encode($composerJson, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
            file_put_contents(base_path('composer.json'), $newComposerJson);


            if ($exitCode) {
                Console::error('');
                Console::error('There was an error publishing the config file: Try running the following command for more details:');
                Console::error('php artisan vendor:publish --provider=' . AkceliServiceProvider::class);
                Console::error('');
                return;
            } else {
                Console::info('The akceli.php config file we published to /config/akceli.php');
                Console::info('akceli/AkceliTableDataTrait.php was published');
                Console::info('akceli/AkceliColumnTrait.php was published');
                return;
            }
        }

        if (is_null($this->argument('template-set'))) {
            $templateSets = array_keys($config['template-groups']);
            if ($config['select-template-behavior'] ?? 'multiple-choice' === 'auto-complete') {
                $template_set = Console::anticipate('What template set do you want to use? (Press enter to see list of options)', $templateSets);
            } else {
                $template_set = Console::choice('What template set do you want to use?', $templateSets);
            }

            if (is_null($template_set)) {
                $template_set = Console::choice('What template set do you want to use?', $templateSets);
            }
        }

        /**
         * Validate the the Template is a valid option
         */
        if (!isset($config['template-groups'][$template_set])) {
            Console::error('');
            Console::error('Invalid Template Set: ' . $template_set . ' dose not exist in your config file.');
            Console::error('');
            return;
        }

        $templateSet = $config['template-groups'][$template_set];
        if (is_string($templateSet)) {
            $templateSet = new $templateSet();
        }

        $extraData = [
            'app_namespace' => Container::getInstance()->getNamespace()
        ];

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

            Console::info("Table Name: {$table_name}");
            Console::info("Model Name: {$model_name}");
        } else {
            $columns = collect([]);
        }

        /**
         * Process Data Prompts
         */
        if ($templateSet['data'] ?? false) {
            $extraData = Console\DataPrompter\DataPrompter::prompt($templateSet['data'], $extraData);
        }

        $template_data = $extraData;

        if ($this->option('dump')) {
            dd($template_data);
        }

        GeneratorService::setData($template_data); // Set Globally for use during relationship generation
        GeneratorService::setFileTemplates($templateSet['templates'] ?? []);
        GeneratorService::setInlineTemplates($templateSet['inline_templates'] ?? []);

        $templateData = new TemplateData($template_data, $columns);
        $generator = new GeneratorService($templateData);
        $generator->generate($this->option('force'));

        if ($templateSet['completion_message'] ?? false) {
            $templateSet['completion_message']();
        }
    }
}
