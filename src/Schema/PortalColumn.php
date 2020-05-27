<?php

namespace Akceli\Schema;

use Illuminate\Support\Str;

class PortalColumn
{
    public string $field;
    public $type;
    public bool $nullable;
    public $default;
    public bool $auto_increments;
    public array $validation_rules;
    public bool $is_unique;
    public ?string $casts_to;

    public function __construct($column)
    {
        $this->field = $column['field'];
        $this->type = $column['type'];
        $this->nullable = $column['nullable'];
        $this->default = $column['default'];
        $this->auto_increments = $column['auto_increments'];
        $this->validation_rules = $column['validation_rules'];
        $this->casts_to = $column['casts_to'];
        $this->is_unique = $column['is_unique'];
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getCastsToAttribute(): string
    {
        return $this->casts_to;
    }

    public function getValidationRulesAsString(): string
    {
        return implode('|', $this->validation_rules);
    }

    public function getValidationRulesAsArray(): array
    {
        return $this->validation_rules;
    }

    public function isIncrementing(): bool
    {
        return $this->auto_increments;
    }

    public function isUnique(): bool
    {
        return $this->is_unique;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function isInteger(): bool
    {
        return $this->type === 'int';
    }

    public function isBoolean(): bool
    {
        return $this->type === 'bool';
    }

    public function isTimeStamp(): bool
    {
        return $this->type === 'timestamp';
    }

    public function isEnum(): bool
    {
        return $this->type === 'enum';
    }

    public function isString(): bool
    {
        return $this->type === 'string';
    }

    public function isIn(array $column_names) {
        return in_array($this->getField(), $column_names);
    }

    public function notIn(array $column_names) {
        return !$this->isIn($column_names);
    }

    public function getSpacedField() {
        return str_replace('-', ' ', Str::kebab($this->getField()));
    }

    function startsWith($needle)
    {
        return Str::startsWith($this->getField(), $needle);
    }

    function endsWith($needle)
    {
        return Str::endsWith($this->getField(), $needle);
    }

    public function getStudlySpacedField()
    {
        return ucwords($this->getSpacedField());
    }
}
