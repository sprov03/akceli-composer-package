<?php

namespace Akceli\Schema\Relationships;

use Akceli\AkceliFileModifier;
use Akceli\Bootstrap\FileModifier;
use Akceli\FileModifiers\AkceliPhpFileModifier;
use Akceli\Schema\Columns\Column;
use Akceli\Schema\SchemaItemInterface;
use App\Models\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;

abstract class AkceliRelationship implements SchemaItemInterface
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    abstract public function getCastTo(): string;

    public function getIsNullable(): bool
    {
        return $this->is_nullable;
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

    protected static function getPrimaryRelatedColumnType(Model $model): Column
    {
        $primaryColumn = self::getPrimaryColumn($model);

        /**
         * Primary Column Relationship Mapper
         */
        if ($coumnType = $primaryColumn->migration_methods[0]->name ?? false) {
            if ($coumnType === 'id') $primaryColumn = Column::unsignedBigInteger();
            if ($coumnType === 'bigIncrements') $primaryColumn = Column::unsignedBigInteger();
            if ($coumnType === 'mediumIncrements') $primaryColumn = Column::unsignedMediumInteger();
            if ($coumnType === 'increments') $primaryColumn = Column::unsignedInteger();
            if ($coumnType === 'smallIncrements') $primaryColumn = Column::unsignedSmallInteger();
            if ($coumnType === 'tinyIncrements') $primaryColumn = Column::unsignedTinyInteger();
        }

        return $primaryColumn;
    }
}