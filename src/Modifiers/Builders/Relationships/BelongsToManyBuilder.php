<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\Console;
use Akceli\FileService;
use Akceli\GeneratorService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\Schema\MysqlSchema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Class BelongsToManyBuilder
 * 
 * @property MysqlSchema $schema
 * @package Akceli\Modifiers\Builders\Relationships
 */
class BelongsToManyBuilder extends Builder implements BuilderInterface
{
    public function build()
    {
        $relationships = $this->schema->getBelongsToManyRelationships();
        if ($relationships->count() !== 2) {
            return;
        }
        
        $relationshipOne = $relationships[0];
        $relationshipTwo = $relationships[1];

        $fileInfoOne = FileService::findByTableName($relationshipOne->REFERENCED_TABLE_NAME);
        $fileInfoTwo = FileService::findByTableName($relationshipTwo->REFERENCED_TABLE_NAME);

        $modelOne = FileService::getClassNameOfFile($fileInfoOne);
        $modelTwo = FileService::getClassNameOfFile($fileInfoOne);


        if (is_null($fileInfoOne)) {
            Artisan::call('akceli:generate model ' . $relationshipOne->REFERENCED_TABLE_NAME);
            $fileInfoOne = FileService::findByTableName($relationshipOne->REFERENCED_TABLE_NAME, true);
        }
        if (is_null($fileInfoTwo)) {
            Artisan::call('akceli:generate model ' . $relationshipTwo->REFERENCED_TABLE_NAME);
            $fileInfoTwo = FileService::findByTableName($relationshipTwo->REFERENCED_TABLE_NAME, true);
        }
        
        /**
         * Update Files
         */
        $this->addMethodToFile(
            $fileInfoOne,
            Str::camel(Str::plural($modelTwo)),
            $this->parser->render('belongsToMany', [
                'relationship' => $relationshipTwo,
                'otherModel' => $modelTwo,
                'table_name' => $this->schema->getTable()
            ])
        );

        $this->addMethodToFile(
            $fileInfoTwo,
            Str::camel(Str::plural($modelOne)),
            $this->parser->render('belongsToMany', [
                'relationship' => $relationshipOne,
                'otherModel' => $modelOne,
                'table_name' => $this->schema->getTable()
            ])
        );

        $this->addUseStatementToFile($fileInfoOne, $fileInfoTwo);
        $this->addUseStatementToFile($fileInfoTwo, $fileInfoOne);

        $this->addClassPropertyDocToFile(
            $fileInfoOne,
            "{$modelTwo}[]|\\Illuminate\\Database\\Eloquent\\Collection",
            Str::plural(Str::camel($modelTwo))
        );
        $this->addClassPropertyDocToFile(
            $fileInfoTwo,
            "{$modelOne}[]|\\Illuminate\\Database\\Eloquent\\Collection",
            Str::plural(Str::camel($modelOne))
        );
    }
}

