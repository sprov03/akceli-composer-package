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
        return ucwords($this->getSpacedField()) . ':';
    }
}
