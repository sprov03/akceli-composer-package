<?php

namespace CrudGenerator\Modifiers\Builders\Relationships;

use CrudGenerator\File;
use CrudGenerator\Modifiers\Builders\Builder;
use CrudGenerator\Modifiers\Builders\BuilderInterface;
use CrudGenerator\Modifiers\ClassModifier;

class HasManyBuilder extends Builder implements BuilderInterface
{
    /**
     * Build sections of files and place them in the files
     *
     * @param \SplFileInfo $fileInfo
     * @param \SplFileInfo $otherFileInfo
     * @param $relationship
     *
     * @return void
     */
    public function updateFiles(
        \SplFileInfo $fileInfo,
        \SplFileInfo $otherFileInfo,
        $relationship
    ) {
        $otherModel = str_singular(studly_case($relationship->TABLE_NAME));

        $this->addMethodToFile(
            $fileInfo,
            camel_case(str_plural($otherModel)),
            $this->parser->render('hasMany', compact('relationship', 'otherModel'))
        );
        $this->addUseStatementToFile($fileInfo, $otherFileInfo);
        $this->addClassPropertyDocToFile(
            $fileInfo,
            "{$otherModel}[]|\\Illuminate\\Database\\Eloquent\\Collection",
            str_plural(camel_case($otherModel))
        );
    }

    public function analise($relationship, $interface = null)
    {
        $file = new File(app_path());

        $fileInfo = $file->findByTableName($relationship->REFERENCED_TABLE_NAME);
        $otherFileInfo = $file->findByTableName($this->schema->getTable());

        $this->updateFiles($fileInfo, $otherFileInfo, $relationship);
    }
}
