<?php

namespace Akceli\Schema\Columns;

use Akceli\Schema\ColumnInterface;
use Akceli\Schema\SchemaInterface;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class Column
 * @package Akceli\Schema\Columns
 * @mixin Blueprint
 */
class SchemaColumnAdapter implements ColumnInterface
{
    private Column $schemColumn;
    public function __construct(Column $column)
    {
        $this->schemColumn = $column;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->schemColumn->column_name;
    }

    /**
     * @return string
     */
    public function getRelationship(): string
    {
        return Str::camel(str_replace('_id', '', $this->getField()));
    }

    /**
     * @return string
     */
    public function getRelatedModel(): string
    {
        return Str::studly(str_replace('_id', '', $this->getField()));
    }

    /**
     * @return boolean
     */
    public function hasCastsToAttribute(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCastsToAttribute(): string
    {
        return $this->schemColumn->casts;
    }

    /**
     * @return bool
     */
    public function hasValidationRules(): bool
    {
        return false;
    }

    public function getValidationRulesAsString(): string
    {
        return '';
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
            if (preg_match("/" . implode('|', $config->ignorePatterns) . "/", $this->getField())) {
                return null;
            }
        }

        if ($this->isInteger()) return $config->integer;
        if ($this->isString()) return $config->string;
        if ($this->isEnum()) return $config->enum;
        if ($this->isTimeStamp()) return $config->timestamp;
        if ($this->isBoolean()) return $config->boolean;
        if ($this->isJson()) return $config->json;

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
        return true;
    }

    public function isNullable(): bool
    {
        return $this->schemColumn->is_nullable;
    }

    public function isInteger(): bool
    {
        return false;
    }

    public function isBoolean(): bool
    {
        return false;
    }

    public function isJson(): bool
    {
        return false;
    }

    public function isTimeStamp(): bool
    {
        return true;
    }

    public function isEnum(): bool
    {
        return false;
    }

    /**
     * Default to striing if all ealse fails
     *
     * @return bool
     */
    public function isString(): bool
    {
        return (
            !$this->isInteger() &&
            !$this->isBoolean() &&
            !$this->isJson() &&
            !$this->isTimeStamp() &&
            !$this->isEnum()
        );
    }

}