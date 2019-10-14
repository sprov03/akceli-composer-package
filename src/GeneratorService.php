<?php

namespace Akceli;

use Akceli\Schema\SchemaFactory;

class GeneratorService
{
    private static $data = [];
    private static $file_templates = [];
    private static $inline_templates = [];

    /**
     * @var TemplateData
     */
    private $templateData;

    /**
     * Service constructor
     * @param TemplateData $templateData
     */
    public function __construct(TemplateData $templateData)
    {
        $this->templateData = $templateData;
    }

    public static function setData(array $data)
    {
        self::$data = $data;
    }

    public static function getData()
    {
        return self::$data;
    }

    public static function setFileTemplates(array $file_templates)
    {
        self::$file_templates = $file_templates;
    }

    public static function setInlineTemplates(array $inline_templates)
    {
        self::$inline_templates = $inline_templates;
    }

    public function generate($force = false)
    {
        Console::info('');
        Console::info("Creating Templates:");
        $templateParser = new Parser(base_path('resources/akceli/templates'), 'akceli.php');
        $templateParser->addData($this->templateData->toArray());
        foreach (self::$file_templates as $template) {
            $template_path = $templateParser->render($template['path']);
            if(file_exists($template_path) && ! $force) {
                Console::warn("File {$template_path} (Already Exists)");

                continue;
            }

            $this->putFile($templateParser->render($template['name']), $template_path);
            Console::info("File {$template_path} (Created)");
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
