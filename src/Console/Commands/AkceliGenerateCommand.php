<?php

namespace Akceli\Console\Commands;

use Akceli\AkceliServiceProvider;
use Akceli\FileService;
use Akceli\GeneratorService;
use Akceli\Console;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AkceliGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akceli {table-name} {template-set?} {--dump} {--force} ' .
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
        $table_name = $this->argument('table-name');
        $model_name = $this->option('model-name');
        $template_set = $this->argument('template-set');
        $config = config('akceli');

        /**
         * If config file has not been published then publish it.
         */
        if (is_null($config)) {
            $exitCode = Artisan::call('vendor:publish', [
                '--provider' => AkceliServiceProvider::class
            ]);

            if ($exitCode) {
                $this->error('');
                $this->error('There was an error publishing the config file: Try running the following command for more details:');
                $this->error('php artisan vendor:publish --provider=' . AkceliServiceProvider::class);
                $this->error('');
                return;
            } else {
                $this->info('The akceli.php config file we published to /config/akceli.php');
                $this->info('Run the command again, and it will work.');
                return;
            }
        }

        if (is_null($this->argument('template-set'))) {
            $templateSets = array_diff(array_keys($config), ['options', 'root_model_path']);
            $template_set = $this->anticipate('What template set do you want to use?', $templateSets);
        }

        /**
         * Validate the the Template is a valid option
         */
        if (!isset($config[$template_set])) {
            $this->error('');
            $this->error('Invalid Template Set: ' . $template_set . ' dose not exist in your config file.');
            $this->error('');
            return;
        }

        $templates = $config[$template_set];

        $extraData = [];
        if ($this->option('extra-data')) {
            foreach (explode('|', str_replace('/', '\\', $this->option('extra-data'))) as $set) {
                $parts = explode(':', $set);
                $extraData[$parts[0]] = $parts[1];
            }
        }

        $extraData = array_merge($config['options'], $templates['options'], $extraData);

        if (is_null($model_name)) {
            $model_name = studly_case(str_singular($table_name));
        }

        /**
         * Setup Global Classes
         */
        Console::setLogger($this);
        FileService::setRootDirectory(base_path(config('akceli.root_model_path')));
        GeneratorService::addExtraData($extraData);
        GeneratorService::setFileTemplates($templates['templates']);
        GeneratorService::setInlineTemplates($templates['inline_templates']);

        $generator = new GeneratorService($table_name, $model_name);

        if ($this->option('only-relationships') && !$this->option('only-templates')) {
            $generator->generate($this->option('force'), $this->option('dump'), false, true);
            return;
        }

        if ($this->option('only-templates') && !$this->option('only-relationships')) {
            $generator->generate($this->option('force'), $this->option('dump'), true, false);
            return;
        }

        $generator->generate($this->option('force'), $this->option('dump'), true, true);
    }
}
