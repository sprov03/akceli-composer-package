<?php

namespace Akceli;

class Akceli
{
    public static function inlineTemplate(string $temtemplate, string $destination_path, string $identifier) {
        return [
            'name' => $temtemplate,
            'path' => $destination_path,
            'identifier' => $identifier,
        ];
    }

    public static function fileTemplate(string $temtemplate, string $destination_path) {
        return [
            'name' => $temtemplate,
            'path' => $destination_path,
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
