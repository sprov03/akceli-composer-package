<?php

namespace Akceli;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GeneratorService
{
    private static $data = [];
    private static $file_templates = [];
    private static $inline_templates = [];
    private static $file_modifiers = [];

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

    public static function setFileModifiers(array $file_modifiers)
    {
        self::$file_modifiers = $file_modifiers;
    }

    public static function setInlineTemplates(array $inline_templates)
    {
        self::$inline_templates = $inline_templates;
    }

    /**
     * @param bool $force
     * @throws \Throwable
     */
    public function generate($force = false)
    {
        Console::info("Creating Templates:");
        /**
         * This is here to build out the templates so that the parser can handle process the php
         * They are deleted at the end of the request
         */
        File::makeDirectory(base_path('akceli/temp'), 0777, true, true);
        foreach (self::$file_templates as $key => $template) {
            File::put(base_path('akceli/temp/' . $key . '.akceli.php'), $template['template']);
        }

        try {
            $templateParser = new Parser(base_path('akceli/temp'), 'akceli.php');
            $templateParser->addData($this->templateData->toArray());

            $this->processFileTemplates($templateParser, $force);
            $this->processInlineTemplates($templateParser);
            $this->processFileModifiers();
        } finally {
            File::deleteDirectory(base_path('akceli/temp'));
        }
    }

    private function processFileTemplates(Parser $parser, bool $force)
    {
        foreach (self::$file_templates as $key => $template) {
            $destination_path = $parser->render($template['destination_path']);
            if(file_exists($destination_path) && ! $force) {
                Console::warn("File {$destination_path} (Already Exists)");

                continue;
            }

            FileService::putFile($destination_path, $parser->render($key));
            Console::info("File {$destination_path} (Created)");
        }
    }

    private function processFileModifiers()
    {
        foreach (self::$file_modifiers as $file_modifier) {
            /** @var AkceliFileModifier $fileModifier */
            $fileModifier = call_user_func(AkceliFileModifier::class . '::' . $file_modifier['type'], base_path($file_modifier['file']));
            foreach ($file_modifier['modifiers'] as $modifier) {
                [$method, $args] = [...$modifier];
                call_user_func([$fileModifier, $method], ...$args);
            }
            $fileModifier->saveChanges();
        }
    }

    private function processInlineTemplates(Parser $parser)
    {
        foreach (self::$inline_templates as $inlineTemplate) {
            $rendered_template = $parser->render($inlineTemplate['content'] ?? $inlineTemplate['name'] ?? '');
            $rendered_template = trim($rendered_template);

            $file_contents = file_get_contents(base_path($inlineTemplate['path']));
            if (! Str::contains($file_contents, $inlineTemplate['identifier'])) {
                Console::error("File {$inlineTemplate['path']} is missing the identifier: " . "{$inlineTemplate['identifier']}");

                continue;
            }

            if (Str::contains($file_contents, $rendered_template)) {
                Console::warn("File {$inlineTemplate['path']} (Already Has Content)");
                continue;
            }

            $escapedIdentifyer = preg_quote($inlineTemplate['identifier']);
            if ($escapedIdentifyer[0] === '/') {
                $escapedIdentifyer = '\\' . $escapedIdentifyer;
            }
            if (substr($escapedIdentifyer, -1) === '/') {
                $escapedIdentifyer = substr_replace($escapedIdentifyer, '\\/', -1);
            }

            $regex = '/([ \t]*)?(' . $escapedIdentifyer . ')/s';
            preg_match_all($regex, $file_contents, $matches, PREG_SET_ORDER, 0);
            $indent = $matches[0][1];

            $file_contents = str_replace(
                $inlineTemplate['identifier'],
                $rendered_template . PHP_EOL . $indent . $inlineTemplate['identifier'],
                $file_contents
            );

            file_put_contents(base_path($inlineTemplate['path']), $file_contents);
            Console::info("File {$inlineTemplate['path']} (Updated)");
        }
    }
}
