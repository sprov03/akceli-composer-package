<?php

use Akceli\Schema\ColumnInterface;

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
}
