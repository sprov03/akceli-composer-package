<?php

namespace Akceli\Schema;

class MysqlColumn implements ColumnInterface
{
    public $Field;
    public $Type;
    public $Null;
    public $Key;
    public $Default;
    public $Extra;
    public $name;
    public $display;

    public $document_type;
    public $type;
    public $rules;
    public $casts;

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
     * @return string
     */
    public function getDataType(): string
    {
        return $this->document_type ?? 'string';
    }
}
