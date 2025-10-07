<?php

namespace Akceli;

use Akceli\Generators\AkceliGenerator;
use Akceli\Schema\SchemaFactory;
use Akceli\GeneratorFlowController;
use Akceli\Parser;
use Illuminate\Support\Str;

class GeneratorService
{
    private static $data = [];
    private static $file_templates = [];
    private static $inline_templates = [];
    public static Parser $paser;

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
        $templateParser = new Parser(base_path('akceli/templates'), 'akceli.php');
        $templateParser->addData($this->templateData->toArray());
        self::$paser = $templateParser;

        $this->processFileTemplates($templateParser, $force);
        $this->processInlineTemplates($templateParser);
    }

    private function processFileTemplates(Parser $parser, bool $force)
    {
        foreach (self::$file_templates as $template) {
            if ($extraData = $template['extra_data'] ?? false) {
                GeneratorService::$paser->addData($extraData);
            }

            $template_path = $parser->render($template['path']);
            if(file_exists($template_path) && ! $force) {
                Console::warn("File {$template_path} (Already Exists)");

                continue;
            }

            FileService::putFile($template_path, $parser->render($template['name']));
            Console::info("File {$template_path} (Created)");
        }
    }

    private function processInlineTemplates(Parser $parser)
    {
        foreach (self::$inline_templates as $inlineTemplate) {
            if ($extraData = $inlineTemplate['extra_data'] ?? false) {
                GeneratorService::$paser->addData($extraData);
            }

            $rendered_template = $parser->render($inlineTemplate['content'] ?? $inlineTemplate['name'] ?? '');

            $file_contents = file_get_contents(base_path($parser->render($inlineTemplate['path'])));

            if (! Str::contains($file_contents, $inlineTemplate['identifier'])) {
                Console::error("File {$inlineTemplate['path']} is missing the identifier: " . "{$inlineTemplate['identifier']}");

                continue;
            }

            if (Str::contains($file_contents, $rendered_template)) {
                Console::warn("File {$inlineTemplate['path']} (Already Has Content)");
                continue;
            }

            $escapedIdentifyer = preg_quote($inlineTemplate['identifier'], '/');

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
