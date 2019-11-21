<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\Modifiers\ClassModifier;
use Illuminate\Support\Str;

class HasOneBuilder extends Builder implements BuilderInterface
{

    public function build()
    {
        return;
    }

    public function buildRelated($relationship, string $relationshipName = null)
    {
        /**
         * Initalize Data
         */
        $fileInfo = FileService::findByTableName($relationship->REFERENCED_TABLE_NAME);
        $otherFileInfo = $this->fileInfo;
        $otherModel = FileService::getClassNameOfFile($otherFileInfo);
        $templateData = [
            'relationship' => $relationship,
            'otherModel' => $otherModel,
            'hasOneMethodName' => ($relationshipName) ?? Str::singular(Str::camel($otherModel))
        ];

        /**
         * Update File
         */
        $this->addMethodToFile($fileInfo, Str::camel(Str::singular($otherModel)), $this->parser->render('hasOne', $templateData));
        $this->addUseStatementToFile($fileInfo, $otherFileInfo);
        $this->addClassPropertyDocToFile($fileInfo, $otherModel, ($relationshipName) ?? Str::camel($otherModel));
    }
}
