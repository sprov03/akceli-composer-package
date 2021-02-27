<?php

namespace Akceli;

use Illuminate\Support\Str;

class Akceli
{

    public static function inlineTemplate(string $template, string $destination_path, string $identifier, array $extra_data = []) {
        return [
            'name' => $template,
            'path' => $destination_path,
            'identifier' => $identifier,
            'extra_data' => $extra_data
        ];
    }

    public static function insertInline(string $destination_path, string $identifier, string $content, array $extra_data = []) {
        return [
            'path' => $destination_path,
            'identifier' => $identifier,
            'content' => $content,
            'extra_data' => $extra_data
        ];
    }

    public static function fileTemplate(string $template, string $destination_path, array $extra_data = []) {
        return [
            'name' => $template,
            'path' => $destination_path,
            'extra_data' => $extra_data
        ];
    }

    public static function columnSetting(string $default = null, string $integer = null, string $string = null, string $enum = null, string $timestamp = null, string $boolean = null, array $ingore_patterns = []) {
        return [
            'default' => $default,
            'integer' => $integer,
            'string' => $string,
            'enum' => $enum,
            'timestamp' => $timestamp,
            'boolean' => $boolean,
            'ignore_patterns' => $ingore_patterns
        ];
    }
}
