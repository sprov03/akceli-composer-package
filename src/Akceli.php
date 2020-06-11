<?php

namespace Akceli;

use Illuminate\Support\Str;

class Akceli
{
    public static function inlineTemplate(string $template, string $destination_path, string $identifier) {
        return [
            'name' => $template,
            'path' => $destination_path,
            'identifier' => $identifier,
        ];
    }

    public static function insertInline(string $destination_path, string $identifier, string $content) {
        return [
            'path' => $destination_path,
            'identifier' => $identifier,
            'content' => $content,
        ];
    }

    public static function fileTemplate(string $template, string $destination_path) {
        return [
            'name' => $template,
            'path' => $destination_path,
        ];
    }

    public static function columnSetting(
        string $default = null,
        string $integer = null,
        string $string = null,
        string $enum = null,
        string $timestamp = null,
        string $boolean = null,
        string $json = null,
        array $ingore_patterns = []
    ) {
        return [
            'default' => $default,
            'integer' => $integer,
            'string' => $string,
            'enum' => $enum,
            'timestamp' => $timestamp,
            'boolean' => $boolean,
            'json' => $json,
            'ignore_patterns' => $ingore_patterns
        ];
    }
}
