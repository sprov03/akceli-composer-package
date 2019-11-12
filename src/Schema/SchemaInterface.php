<?php

namespace Akceli\Schema;

use Illuminate\Support\Collection;

interface SchemaInterface
{
    /**
     * Schema constructor
     *
     * @param string $table
     */
    function __construct(string $table);

    /**
     * @return ColumnInterface[]|Collection
     */
    public function getColumns(): Collection;

    /**
     * @return Collection
     */
    public function getBelongsToRelationships(): Collection;

    /**
     * @return Collection
     */
    public function getBelongsToManyRelationships(): Collection;

    public function getPolymorphicRelationships();
}
