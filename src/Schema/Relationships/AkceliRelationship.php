<?php

namespace Akceli\Schema\Relationships;

use Akceli\AkceliFileModifier;
use Akceli\Bootstrap\FileModifier;
use Akceli\FileModifiers\AkceliPhpFileModifier;
use Akceli\Schema\Columns\Column;
use App\Models\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;

abstract class AkceliRelationship
{
    public string $name;
    public bool $is_nullable = false;

    /** @var Column[] $columns */
    public array $columns = [];

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param bool $is_nullable
     */
    public function nullable(): self
    {
        $this->is_nullable = true;

        foreach ($this->columns as $column){
            $column->nullable();
        }

        return $this;
    }

    /**
     * @param Column $column
     */
    public function pushColumn(Column $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    abstract public function addToModel(AkceliPhpFileModifier $fileModifier);

    /**
     * @param BaseModelTrait $model
     * @return Column
     * @throws \Exception
     */
    protected static function getPrimaryColumn(Model $model): Column
    {
        try {
            /** @var Column $primaryColumn */
            $primaryColumn = $model->schema()[$model->getKeyName()];
        } catch (\Throwable $throwable) {
            throw new \Exception('The related model dose not have a schema column for: ' . $model->getKeyName());
        }

        return $primaryColumn;
    }
}