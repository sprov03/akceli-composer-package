<?php

namespace CrudGenerator\Console\Commands;

use CrudGenerator\Service;
use Illuminate\Console\Command;

class TemplateGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:templates {table-name} {--dump} {--force} {--model-name=} ' .
    '{--template-set=} {--other-variables=} {--namespace=}';

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
     */
    public function handle()
    {
        $table_name = $this->argument('table-name');
        $model_name = $this->option('model-name');
        $template_set = $this->option('template-set') ? $this->option('template-set') : 'default';
        $templates = config("crud.$template_set");

        if (is_null($templates)) {
            throw new \InvalidArgumentException('that is an invalid template option');
        }

        $other_variables = [];
        if ($this->option('other-variables')) {
            foreach (explode('|', str_replace('/', '\\', $this->option('other-variables'))) as $set) {
                $parts = explode(':', $set);
                $other_variables[$parts[0]] = $parts[1];
            }
        }

        if ($this->option('namespace')) {
            $other_variables['namespace'] = $this->option('namespace');
            $other_variables['namespace_path'] = str_replace('\\', '/', $this->option('namespace'));
        }

        $other_variables = array_merge(config('crud.options'), $templates['options'], $other_variables);

        if (is_null($model_name)) {
            $model_name = studly_case(str_singular($table_name));
        }

        $GLOBALS['options'] = $other_variables;
        $GLOBALS['template_set'] = $templates;

        $generator = new Service($table_name, $model_name, $other_variables, $output = $this);

        $generator->Generate($templates, $this->option('force'), $this->option('dump'), true, false);
    }

}
