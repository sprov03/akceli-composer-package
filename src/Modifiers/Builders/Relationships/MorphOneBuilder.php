<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Illuminate\Support\Str;

class MorphOneBuilder extends Builder implements BuilderInterface
{
    /**
     * Build sections of files and place them in the files
     *
     * @param \SplFileInfo $fileInfo
     * @param \SplFileInfo $interfaceFileInfo
     * @param \SplFileInfo $traitFileInfo
     * @param $interface
     * @param $relationship
     *
     * @return void
     */
    public function buildRelated(
        \SplFileInfo $fileInfo,
        \SplFileInfo $interfaceFileInfo,
        \SplFileInfo $traitFileInfo,
        $interface,
        $relationship,
        $reverseRelationshipName
    ) {
        $OtherModel = FileService::getClassNameOfFile($fileInfo);
        $otherModel = Str::camel($OtherModel);
        $reverseRelationshipName = ($reverseRelationshipName) ?? Str::singular(Str::camel($otherModel));

        $this->addAbstractMethodToFile(
            $interfaceFileInfo,
            $reverseRelationshipName,
            $this->parser->render('morphOne', compact('relationship', 'otherModel', 'OtherModel', 'reverseRelationshipName'))
        );
        $this->addMethodToFile(
            $traitFileInfo,
            $reverseRelationshipName,
            $this->parser->render('morphOne', compact('relationship', 'otherModel', 'OtherModel', 'reverseRelationshipName'))
        );

        $this->addUseStatementToFile($interfaceFileInfo, $fileInfo);
        $this->addUseStatementToFile($traitFileInfo, $fileInfo);

        $docType = $OtherModel;
        $variable = $reverseRelationshipName;
        $this->addClassPropertyDocToFile($interfaceFileInfo, $docType, $variable);
        $this->addClassPropertyDocToFile($traitFileInfo, $docType, $variable);
    }

    public function build()
    {
        return;
    }
}
