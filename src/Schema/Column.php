<?php

namespace Akceli\Schema;

class Column
{
    private $Field;
    private $Type;
    private $Null;
    private $Key;
    private $Default;
    private $Extra;
    private $name;
    private $display;
    private $document_type;
    private $type;
    private $rules;
    private $casts;

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
        return $this->casts;
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
        return $this->rules;
    }

    /**
     * @return string
     */
    public function getDataType(): string
    {
        return $this->document_type ?? 'string';
    }
}
