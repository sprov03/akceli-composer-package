<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\GeneratorService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;

class BelongsToManyBuilder extends Builder implements BuilderInterface
{
    /**
     * Build sections of files and place them in the files
     *
     * @param \SplFileInfo $fileInfoOne
     * @param $relationshipOne
     * @param \SplFileInfo $fileInfoTwo
     * @param $relationshipTwo
     *
     * @return void
     */
    public function updateFiles(
        \SplFileInfo $fileInfoOne,
        $relationshipOne,
        \SplFileInfo $fileInfoTwo,
        $relationshipTwo
    ) {
        $modelOne = Str::singular(Str::studly($relationshipOne->REFERENCED_TABLE_NAME));
        $modelTwo = Str::singular(Str::studly($relationshipTwo->REFERENCED_TABLE_NAME));

        $this->addMethodToFile(
            $fileInfoOne,
            Str::camel(Str::plural($modelTwo)),
            $this->parser->render('belongsToMany', [
                'relationship' => $relationshipTwo,
                'otherModel' => $modelTwo
            ])
        );

        $this->addMethodToFile(
            $fileInfoTwo,
            Str::camel(Str::plural($modelOne)),
            $this->parser->render('belongsToMany', [
                'relationship' => $relationshipOne,
                'otherModel' => $modelOne
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

    public function analise($relationships, $interface = null)
    {
        $relationshipOne = $relationships[0];
        $relationshipTwo = $relationships[1];

        $fileInfoOne = FileService::findByTableName($relationshipOne->REFERENCED_TABLE_NAME);
        $fileInfoTwo = FileService::findByTableName($relationshipTwo->REFERENCED_TABLE_NAME);

        if (is_null($fileInfoOne)) {
            (new GeneratorService($relationshipOne->REFERENCED_TABLE_NAME, $fileInfoOne->getFilename()))
                ->generate(false, false, true, true);
            $fileInfoOne = FileService::findByTableName($relationshipOne->REFERENCED_TABLE_NAME, true);
        }
        if (is_null($fileInfoTwo)) {
            (new GeneratorService($relationshipTwo->REFERENCED_TABLE_NAME, $fileInfoTwo->getFilename()))
                ->generate(false, false, true, true);
            $fileInfoTwo = FileService::findByTableName($relationshipTwo->REFERENCED_TABLE_NAME, true);
        }

        $this->updateFiles($fileInfoOne, $relationshipOne, $fileInfoTwo, $relationshipTwo);
    }
}

