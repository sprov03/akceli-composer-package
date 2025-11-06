<?php

use Akceli\Schema\ColumnInterface;
use Illuminate\Support\Str;

/**
 * Class AkceliColumnTrait
 *
 * @mixin ColumnInterface
 */
trait AkceliColumnTrait
{
    public function isIn(array $column_names) {
        return in_array($this->getField(), $column_names);
    }

    public function notIn(array $column_names) {
        return !$this->isIn($column_names);
    }

    public function getValidationRulesAsArray()
    {
        // Max Validation Rule For Date is added so we generate all of the proper validation rules
        $validationString = str_replace("date", "date|after:1970-01-01 00:00:00|before:2038-01-19 03:14:07", $this->getValidationRulesAsString());
        return "['" . str_replace('|', "', '", $validationString) . "']";
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

    public function getClientLabel()
    {
        return ucwords(str_replace('_', ' ', $this->getField()));
    }

    public function isRelation()
    {
        return $this->endsWith('_id');
    }

    public function toRelation()
    {
        return Str::camel(str_replace('_id', '', $this->getField()));
    }
}
