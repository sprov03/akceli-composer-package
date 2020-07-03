<?php

namespace Akceli;

use Akceli\Generators\AkceliGenerator;
use Akceli\Parser;
use Akceli\Schema\SchemaFactory;
use Akceli\GeneratorFlowController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RemoteGeneratorService
{
    private static $data = [];
    private static $file_templates = [];
    private static $file_modifiers = [];

    const TEMP_DIR = __DIR__ . '/../temp';

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
        if (File::exists(self::TEMP_DIR)) {
            self::removeFileTemplates();
        }

        File::makeDirectory(self::TEMP_DIR);
        self::$file_templates = $file_templates;
        foreach ($file_templates as $template) {
            File::put(self::TEMP_DIR . '/' . $template['name'] . '.akceli.php', $template['body']);
        }
    }

    public static function removeFileTemplates()
    {
        File::deleteDirectory(self::TEMP_DIR);
    }

    public static function setFileModifiers(array $file_modifiers)
    {
        self::$file_modifiers = $file_modifiers;
    }

    public function generate($force = false)
    {
        $templateParser = new Parser(self::TEMP_DIR, 'akceli.php');
        $templateParser->addData($this->templateData->toArray());

        $this->processFileTemplates($templateParser, $force);
        $this->processFileModifiers($templateParser, $force);
    }

    private function processFileTemplates(Parser $parser, bool $force)
    {
        foreach (self::$file_templates as $template) {
            // Resolving the path with the data
            $template_path = $parser->render($template['pivot']['destination_path']);
            if(file_exists($template_path) && ! $force) {
                Console::warn("File {$template_path} (Already Exists)");

                continue;
            }

            FileService::putFile($template_path, $parser->render($template['name']));
            Console::info("File {$template_path} (Created)");
        }
    }

    private function processFileModifiers(Parser $parser, bool $force)
    {
        foreach (self::$file_modifiers as $file_modifier) {
            $fileModifier = call_user_func_array(
                [AkceliFileModifier::class, $file_modifier['file_type']],
                [$parser->render($file_modifier['path'])]
            );

            // This should be in a loop
            $modification = $file_modifier['modification'];
            $props = array_map(function ($prop) {
                return $parser->render($prop['value']);
            }, $modification['props']);

            call_user_func_array(
                [$fileModifier, $modification['type']],
                $props
            );

            $fileModifier->saveChanges();
        }
    }

    public static function processPrompts(array $prompts = [], array $data)
    {
        foreach ($prompts as $prompt) {
            if ($prompt['prompt_type'] === 'ask') {
                $data[$prompt['variable']] = Console::ask($prompt['prompt_message'], $prompt['default']);
            } elseif ($prompt['prompt_type'] === 'choice') {
                $data[$prompt['variable']] = Console::choice($prompt['prompt_message'], $prompt['prompt_options'], $prompt['default']);
            } elseif ($prompt['prompt_type'] === 'custom_code') {
                $data[$prompt['variable']] = eval(str_replace('Console::', '\Akceli\\Console::', $prompt['prompt_message']));
            }
        }

        return $data;
    }
}
