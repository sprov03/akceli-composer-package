<?php

namespace Akceli\Console\Commands;

use Akceli\AkceliServiceProvider;
use Akceli\GeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AkceliGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:full {table-name} {--dump} {--force} {--only-relationships} {--only-templates} {--model-name=} {--namespace=}' .
                                      '{--template-set=} {--other-variables=}';

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
        $table_name = $this->argument('table-name');
        $model_name = $this->option('model-name');
        $template_set = $this->option('template-set') ? $this->option('template-set') : 'default';
        $config = config('akceli');

        /**
         * If config file has not been published then publish it.
         */
        if (is_null($config)) {
            $exitCode = Artisan::call('vendor:publish', [
                '--provider' => AkceliServiceProvider::class
            ]);

            if ($exitCode) {
                $this->error('There was an error publishing the config file: Try running the following command for more details:');
                $this->error('php artisan vendor:publish --provider=' . AkceliServiceProvider::class);
                return;
            } else {
                $this->info('The akceli.php config file we published to /config/akceli.php');
            }
        }

        /**
         * Validate the the Template is a valid option
         */
        if (!isset($config[$template_set])) {
            $this->error('Invalid Template Set: ' . $template_set . ' dose not exist in your config file.');
            return;
        }

        $templates = $config[$template_set];

        $other_variables = [];
        if ($this->option('other-variables')) {
            foreach (explode('|', str_replace('/', '\\', $this->option('other-variables'))) as $set) {
                $parts = explode(':', $set);
                $other_variables[$parts[0]] = $parts[1];
            }
        }

        // TODO: Is this necessary? or even helpful?
        if ($this->option('namespace')) {
            $other_variables['namespace'] = $this->option('namespace');
            $other_variables['namespace_path'] = str_replace('\\', '/', $this->option('namespace'));
        }
        $other_variables = array_merge($config['options'], $templates['options'], $other_variables);

        if (is_null($model_name)) {
            $model_name = studly_case(str_singular($table_name));
        }

        /**
         * TODO: this should be static on the Generator Service
         */
        $GLOBALS['akceli_options'] = $other_variables;
        $GLOBALS['akceli_template_set'] = $templates;

        /**
         * todo When getting the model name threw relationships need to make sure i get the Actual Model name instead of the expected one
         */
        $generator = new GeneratorService($table_name, $model_name, $other_variables, $output = $this);

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
