<?php

namespace Akceli\Config;

/**
 * Class ColumnSettingsConfig
 *
 * @property array|string[] $ignorePatterns
 * @property string $integer
 * @property string $string
 * @property string $enum
 * @property string $timestamp
 * @property string $boolean
 */
class ColumnSettingsConfig
{
    public $ignorePatterns;
    public $integer;
    public $string;
    public $enum;
    public $timestamp;
    public $boolean;

    public function __construct(array $config)
    {
        $this->ignorePatterns = $config['ignore_patterns'] ?? [];
        $this->integer = $config['integer'] ?? null;
        $this->string = $config['string'] ?? null;
        $this->enum = $config['enum'] ?? null;
        $this->timestamp = $config['timestamp'] ?? '\Carbon\Carbon';
        $this->boolean = $config['boolean'] ?? 'boolean';
    }
}
