<?php

namespace Akceli;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class GeneratorService
{
    private $table_name;
    private $model_name;
    /**
     * @var array
     */
    private $options;

    /** @var Command */
    private $output;

    /**
     * Service constructor
     *
     * @param string $table_name
     * @param string $model_name
     * @param array $options
     * @param $output
     */
    public function __construct($table_name, $model_name = null, $options = [], Command $output = null)
    {
        $this->table_name = $table_name;
        $this->model_name = $model_name;
        $this->options = $options;
        $this->output = $output;
    }

    public function generate($force = false, $dump = false, $generateTemplates = false, $generateRelationships = false)
    {
        $this->output->info('');
        $this->output->info("Creating Templates:");
        $this->output->info("Table Name: {$this->table_name}");
        $this->output->info("Model Name: {$this->model_name}");

        $templates = $GLOBALS['akceli_template_set']['templates'];
        $inlineTemplates = $GLOBALS['akceli_template_set']['inline_templates'];
        $schema = new Schema($this->table_name, $this->output);
        $template_variables = $this->getTemplateVariables();
        $template_variables['columns'] = $schema->getColumns();
        $template_variables['primary_key'] = $schema->getColumns()
            ->firstWhere('Key', '==', 'PRI')->Field;

        /**
         * To dump options to see what you can have available in your templates
         */
        if ($dump) {
            dd($template_variables);
        }

        $templateParser = new Parser(base_path('akceli/templates'), 'tpl.php');
        $templateParser->addData($template_variables);


        if ($generateTemplates) {
            foreach ($templates as $template) {
                $template_path = $templateParser->render($template['path']);
                if(file_exists($template_path) && ! $force) {
                    $this->output->info("File {$template_path} already exists");

                    continue;
                }

                $this->putFile($templateParser->render($template['name']), $template_path);
                $this->output->info("File {$template_path} Created");
            }

            foreach ($inlineTemplates as $inlineTemplate) {
                $rendered_template = $templateParser->render($inlineTemplate['name']);
                $file_contents = file_get_contents(base_path($inlineTemplate['path']));
                if (! str_contains($file_contents, $inlineTemplate['identifier'])) {
                    $this->output->error("File {$inlineTemplate['path']} is missing the identifier: " .
                        "{$inlineTemplate['identifier']}");

                    continue;
                }

                if (str_contains($file_contents, $rendered_template)) {
                    continue;
                }

                $file_contents = str_replace(
                    $inlineTemplate['identifier'],
                    $rendered_template . PHP_EOL . $inlineTemplate['identifier'],
                    $file_contents
                );

                file_put_contents(base_path($inlineTemplate['path']), $file_contents);
            }
        }

        $classParser = new Parser(base_path('resources/templates/relationship-methods'), 'tpl.php');
        $classParser->addData($template_variables);

        if ($generateRelationships) {
            (new GeneratorFlowController($classParser, $schema, $this->output, $force))->start();
        }
    }

    protected function getTemplateVariables()
    {
        $template_variables = [
            'open_php_tag' => "<?php",
            'table_name' => $this->table_name,

            'ModelName' => $this->model_name,
            'ModelNames' => str_plural(studly_case($this->model_name)),
            'modelName' => camel_case($this->model_name),
            'modelNames' => str_plural(camel_case($this->model_name)),
            'model_name' => snake_case($this->model_name),
            'model_names' => str_plural(snake_case($this->model_name)),
            'model-name' => str_replace('_', '-', snake_case($this->model_name)),
            'model-names' => str_replace('_', '-', str_plural(snake_case($this->model_name))),

            'app_namespace' => Container::getInstance()->getNamespace(),
        ];

        foreach ($this->options as $key => $value) {
            $parser = new Parser();
            $parser->addData($template_variables);

            $template_variables[$key] = $parser->render($value);
        }

        return $template_variables;
    }

    protected function putFile($content, $path)
    {
        $nodes = explode('/', $path);

        $path = base_path();
        $file_name = array_pop($nodes);

        foreach ($nodes as $node) {
            $path .= "/{$node}";

            if (! file_exists($path)) {
                mkdir($path);
            }
        }

        $path .= "/{$file_name}";

        file_put_contents($path, $content);
    }

}
