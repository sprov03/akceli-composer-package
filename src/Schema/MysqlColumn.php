<?php

namespace Akceli\Schema;

use Akceli\Config\ColumnSettingsConfig;
use Akceli\Console;
use AkceliColumnTrait;
use Illuminate\Support\Str;

class MysqlColumn implements ColumnInterface
{
    use AkceliColumnTrait;

    public $Field;
    public $Type;
    public $Null;
    public $Key;
    public $Default;
    public $Extra;

    /**
     * Schema Info
     */
    public $CONSTRAINT_CATALOG;
    public $CONSTRAINT_SCHEMA;
    public $CONSTRAINT_NAME;
    public $TABLE_CATALOG;
    public $TABLE_SCHEMA;
    public $TABLE_NAME;
    public $COLUMN_NAME;
    public $ORDINAL_POSITION;
    public $POSITION_IN_UNIQUE_CONSTRAINT;
    public $REFERENCED_TABLE_SCHEMA;
    public $REFERENCED_TABLE_NAME;
    public $REFERENCED_COLUMN_NAME;

    public $rules;

    public function __construct($column)
    {
        $this->Field = $column->Field;
        $this->Type = $column->Type;
        $this->Null = $column->Null;
        $this->Key = $column->Key;
        $this->Default = $column->Default;
        $this->Extra = $column->Extra;
        $this->CONSTRAINT_CATALOG = $column->CONSTRAINT_CATALOG ?? null;
        $this->CONSTRAINT_SCHEMA = $column->CONSTRAINT_SCHEMA ?? null;
        $this->CONSTRAINT_NAME = $column->CONSTRAINT_NAME ?? null;
        $this->TABLE_CATALOG = $column->TABLE_CATALOG ?? null;
        $this->TABLE_SCHEMA = $column->TABLE_SCHEMA ?? null;
        $this->TABLE_NAME = $column->TABLE_NAME ?? null;
        $this->COLUMN_NAME = $column->COLUMN_NAME ?? null;
        $this->ORDINAL_POSITION = $column->ORDINAL_POSITION ?? null;
        $this->POSITION_IN_UNIQUE_CONSTRAINT = $column->POSITION_IN_UNIQUE_CONSTRAINT ?? null;
        $this->REFERENCED_TABLE_SCHEMA = $column->REFERENCED_TABLE_SCHEMA ?? null;
        $this->REFERENCED_TABLE_NAME = $column->REFERENCED_TABLE_NAME ?? null;
        $this->REFERENCED_COLUMN_NAME = $column->REFERENCED_COLUMN_NAME ?? null;
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

        if (config("akceli.debugging")) {
            Console::info(
                "Column info not identified for: {$this->Type} " .
                json_encode($this, JSON_PRETTY_PRINT)
            );
        }

        return $default;
    }

    public function isIncrementing(): bool
    {
        return $this->Extra === 'auto_increment';
    }

    public function isNullable(): bool
    {
        return $this->Null === 'YES';
    }

    public function isInteger(): bool
    {
        return preg_match('/^(big)?int\((\d*)\)( unsigned)?/', $this->Type);
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
        return Str::contains($this->Type, 'enum(');
    }

    public function isString(): bool
    {
        return preg_match('/^(var)?char\((\d*)\)$|^text$/', $this->Type);
    }

}
