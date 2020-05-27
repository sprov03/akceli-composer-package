<?php

namespace Akceli\Schema;

use Illuminate\Support\Collection;

class PortalSchema
{
    private array $schemaInfo;

    private string $table;
    /** @var  Collection|MysqlColumn[] */
    private Collection $columns;

    /**
     * Schema constructor
     *
     * @param string $table
     */
    function __construct(string $table)
    {
        $this->table = $table;
    }

    public function setSchemaInfo(array $schemaInfo)
    {
        $this->schemaInfo = $schemaInfo;
        $this->columns = collect($this->schemaInfo['columns'])->map(function ($column) {
            return new PortalColumn($column);
        });
    }

    /**
     * Table name
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return PortalColumn[]|Collection
     */
    public function getColumns(): Collection
    {
        return $this->columns;
    }
}
