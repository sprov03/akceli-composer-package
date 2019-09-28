<?php

namespace Akceli;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class GeneratorService
{
    private static $extra_data = [];
    private static $file_templates = [];
    private static $inline_templates = [];

    private $table_name;
    private $model_name;

    /** @var Command */
    private $output;

    /**
     * Service constructor
     *
     * @param string $table_name
     * @param string $model_name
     * @param $output
     */
    public function __construct($table_name, $model_name = null, Command $output = null)
    {
        $this->table_name = $table_name;
        $this->model_name = $model_name;
        $this->output = $output;
    }

    public static function addExtraData(array $extra_data)
    {
        self::$extra_data = $extra_data;
    }

    public static function setFileTemplates(array $file_templates)
    {
        self::$file_templates = $file_templates;
    }

    public static function setInlineTemplates(array $inline_templates)
    {
        self::$inline_templates = $inline_templates;
    }

    public function generate($force = false, $dump = false, $generateTemplates = false, $generateRelationships = false)
    {
        $this->output->info('');
        $this->output->info("Creating Templates:");
        $this->output->info("Table Name: {$this->table_name}");
        $this->output->info("Model Name: {$this->model_name}");

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
            foreach (self::$file_templates as $template) {
                $template_path = $templateParser->render($template['path']);
                if(file_exists($template_path) && ! $force) {
                    $this->output->info("File {$template_path} already exists");

                    continue;
                }

                $this->putFile($templateParser->render($template['name']), $template_path);
                $this->output->info("File {$template_path} Created");
            }

            foreach (self::$inline_templates as $inlineTemplate) {
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

        foreach (self::$extra_data as $key => $value) {
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
