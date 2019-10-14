<?php

namespace Akceli\Schema;

use Akceli\Config\ColumnSettingsConfig;
use Akceli\Console;
use AkceliColumnTrait;

class MysqlColumn implements ColumnInterface
{
    use AkceliColumnTrait;

    public $Field;
    public $Type;
    public $Null;
    public $Key;
    public $Default;
    public $Extra;

    public $rules;

    public function __construct($column)
    {
        $this->Field = $column->Field;
        $this->Type = $column->Type;
        $this->Null = $column->Null;
        $this->Key = $column->Key;
        $this->Default = $column->Default;
        $this->Extra = $column->Extra;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->Field;
    }

    /**
     * @return boolean
     */
    public function hasCastsToAttribute(): bool
    {
        return isset($this->casts);
    }

    /**
     * @return string
     */
    public function getCastsToAttribute(): string
    {
        return $this->casts ?? '';
    }

    /**
     * @return bool
     */
    public function hasValidationRules(): bool
    {
        return isset($this->rules);
    }

    /**
     * @return string
     */
    public function getValidationRulesAsString(): string
    {
        return $this->rules ?? '';
    }

    /**
     * @param string $column_setting
     * @param null $default
     * @return string|null
     */
    public function getColumnSetting(string $column_setting, $default = null)
    {
        if (!config("akceli.column-settings.{$column_setting}")) {
            Console::info("Invalid Column Setting: {$column_setting}");
        }

        $config = new ColumnSettingsConfig(config("akceli.column-settings.{$column_setting}", []));

        if (count($config->ignorePatterns)) {
            if (preg_match("/" . implode('|', $config->ignorePatterns) . "/", $this->Field)) {
                return null;
            }
        }

        if ($this->isInteger()) return $config->integer;
        if ($this->isString()) return $config->string;
        if ($this->isEnum()) return $config->enum;
        if ($this->isTimeStamp()) return $config->timestamp;
        if ($this->isBoolean()) return $config->boolean;

        Console::info(
            "Column info not identified for: {$this->Type} " .
            json_encode($this, JSON_PRETTY_PRINT)
        );

        return $default;
    }

    public function isInteger(): bool
    {
        return preg_match('/^(big)?int\((\d*)\)/', $this->Type);
    }

    public function isBoolean(): bool
    {
        return preg_match('/^tinyint\((\d*)\)/', $this->Type);
    }

    public function isTimeStamp(): bool
    {
        return preg_match('/^timestamp$/', $this->Type);
    }

    public function isEnum(): bool
    {
        return str_contains($this->Type, 'enum(');
    }

    public function isString(): bool
    {
        return preg_match('/^varchar\((\d*)\)$|^text$/', $this->Type);
    }

}
