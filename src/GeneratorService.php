<?php

namespace Akceli;

use Akceli\Schema\SchemaFactory;

class GeneratorService
{
    private static $extra_data = [];
    private static $file_templates = [];
    private static $inline_templates = [];

    private $table_name;
    private $model_name;

    /**
     * Service constructor
     *
     * @param string $table_name
     * @param string $model_name
     */
    public function __construct($table_name, $model_name = null)
    {
        $this->table_name = $table_name;
        $this->model_name = $model_name;
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
        Console::info('');
        Console::info("Creating Templates:");

        $schema = SchemaFactory::resolve($this->table_name);
        $templateData = new TemplateData(
            $this->table_name,
            $this->model_name,
            $schema->getColumns(),
            self::$extra_data
        );

        /**
         * To dump options to see what you can have available in your templates
         */
        if ($dump) {
            dd($templateData->toArray());
        }

        if ($generateTemplates) {
            $templateParser = new Parser(base_path('resources/akceli/templates'), 'akceli.php');
            $templateParser->addData($templateData->toArray());
            foreach (self::$file_templates as $template) {
                $template_path = $templateParser->render($template['path']);
                if(file_exists($template_path) && ! $force) {
                    Console::info("File {$template_path} already exists");

                    continue;
                }

                $this->putFile($templateParser->render($template['name']), $template_path);
                Console::info("File {$template_path} Created");
            }

            foreach (self::$inline_templates as $inlineTemplate) {
                $rendered_template = $templateParser->render($inlineTemplate['name']);
                $file_contents = file_get_contents(base_path($inlineTemplate['path']));
                if (! str_contains($file_contents, $inlineTemplate['identifier'])) {
                    Console::error("File {$inlineTemplate['path']} is missing the identifier: " .
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


        if ($generateRelationships) {
            $classParser = new Parser(base_path('resources/templates/relationship-methods'), 'akceli.php');
            $classParser->addData($templateData->toArray());
            (new GeneratorFlowController($classParser, $schema, $force))->start();
        }
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
