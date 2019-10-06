<?php

use Akceli\Schema\ColumnInterface;
use Akceli\TemplateData;
use Illuminate\Support\Collection;

/**
 * Class AkceliTableDataTrait
 *
 * @property string $namespace
 * @property string $namespace_path
 * @property string $fully_qualified_base_model_name
 * @property string $base_model
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
