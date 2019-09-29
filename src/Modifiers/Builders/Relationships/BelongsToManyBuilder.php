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
        $modelOne = str_singular(studly_case($relationshipOne->REFERENCED_TABLE_NAME));
        $modelTwo = str_singular(studly_case($relationshipTwo->REFERENCED_TABLE_NAME));

        $this->addMethodToFile(
            $fileInfoOne,
            camel_case(str_plural($modelTwo)),
            $this->parser->render('belongsToMany', [
                'relationship' => $relationshipTwo,
                'otherModel' => $modelTwo
            ])
        );

        $this->addMethodToFile(
            $fileInfoTwo,
            camel_case(str_plural($modelOne)),
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
            str_plural(camel_case($modelTwo))
        );
        $this->addClassPropertyDocToFile(
            $fileInfoTwo,
            "{$modelOne}[]|\\Illuminate\\Database\\Eloquent\\Collection",
            str_plural(camel_case($modelOne))
        );
    }

    public function analise($relationships, $interface = null)
    {
        $file = new FileService(app_path());

        $relationshipOne = $relationships[0];
        $relationshipTwo = $relationships[1];

        $fileInfoOne = $file->findByTableName($relationshipOne->REFERENCED_TABLE_NAME);
        $fileInfoTwo = $file->findByTableName($relationshipTwo->REFERENCED_TABLE_NAME);

        if (is_null($fileInfoOne)) {
            (new GeneratorService($relationshipOne->REFERENCED_TABLE_NAME, $fileInfoOne->getFilename()))
                ->generate(false, false, true, true);
            $fileInfoOne = $file->findByTableName($relationshipOne->REFERENCED_TABLE_NAME, true);
        }
        if (is_null($fileInfoTwo)) {
            (new GeneratorService($relationshipTwo->REFERENCED_TABLE_NAME, $fileInfoTwo->getFilename()))
                ->generate(false, false, true, true);
            $fileInfoTwo = $file->findByTableName($relationshipTwo->REFERENCED_TABLE_NAME, true);
        }

        $this->updateFiles($fileInfoOne, $relationshipOne, $fileInfoTwo, $relationshipTwo);
    }
}

