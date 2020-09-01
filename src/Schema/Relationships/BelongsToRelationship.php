<?php

namespace Akceli\Schema\Relationships;

use Akceli\AkceliFileModifier;
use Akceli\Bootstrap\FileModifier;
use Akceli\FileModifiers\AkceliPhpFileModifier;
use Akceli\Schema\Columns\Column;
use Akceli\Schema\Columns\MigrationMethod;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class BelongsToRelationship extends AkceliRelationship
{
    private Model $relatedModel;
    private string $onDelete;

    /**
     * BelongsToRelationship constructor.
     * @param Model $relatedModel
     * @param string $onDelete 'RESTRICT'|'CASCADE'|'SET NULL'
     */
    public function __construct(Model $relatedModel, string $onDelete = 'RESTRICT')
    {
        $this->relatedModel = $relatedModel;
        $this->onDelete = $onDelete;
    }

    public function setName(string $name): AkceliRelationship
    {
        $this->pushColumn(self::getPrimaryRelatedColumnType($this->relatedModel)->setName(Str::snake($name) . '_id'));

        $this->pushColumn(
            Column::foreign($this->relatedModel)
                ->setName(Str::snake($name) . '_id')
                ->pushMigrationMethod(new MigrationMethod('references', [$this->relatedModel->getKeyName()]))
                ->pushMigrationMethod(new MigrationMethod('on', [$this->relatedModel->getTable()]))
                ->pushMigrationMethod(new MigrationMethod('onDelete', [$this->onDelete]))
        );

        return parent::setName($name);
    }

    public function addToModel(AkceliPhpFileModifier $fileModifier)
    {
        $relatedModelName = (new \ReflectionClass($this->relatedModel))->getShortName();
        $columnName = Str::snake($this->name) . '_id';

        $fileModifier->addClassPropertyDocToFile($relatedModelName, $this->name, 'Relationships');
        $fileModifier->addUseStatementToFile('\Illuminate\Database\Eloquent\Relations\BelongsTo');
        $fileModifier->addMethodToFile($this->name, <<<Relationship
            /**
             * Relationship to the {$relatedModelName} Model
             *
             * @return BelongsTo|{$relatedModelName}
             */
            public function {$this->name}()
            {
                return \$this->belongsTo({$relatedModelName}::class, '{$columnName}', '{$this->relatedModel->getKeyName()}');
            }
        Relationship
        );
    }

    /**
     * @return Model
     */
    public function getRelatedModel(): Model
    {
        return $this->relatedModel;
    }

    public function getCastTo(): string
    {
        return get_class($this->getRelatedModel());
    }

    public function getDataType(): string
    {
        return get_class($this->getRelatedModel());
    }
}