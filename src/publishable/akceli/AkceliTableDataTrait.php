<?php

use Akceli\Schema\ColumnInterface;
use Akceli\TemplateData;
use Illuminate\Support\Collection;

/**
 * Class AkceliTableDataTrait
 *
 * @mixin TemplateData
 */
trait AkceliTableDataTrait
{
    /**
     * @param Collection|ColumnInterface[] $columns
     * @return Collection
     */
    public function filterDates(Collection $columns) {
        return $columns->filter(function (ColumnInterface $column) {
            return !$column->isTimeStamp();
        });
    }
}
