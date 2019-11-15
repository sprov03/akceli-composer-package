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
        $relationship
    ) {
        $OtherModel = FileService::getClassNameOfFile($fileInfo);
        $otherModel = Str::camel($OtherModel);

        $this->addAbstractMethodToFile(
            $interfaceFileInfo,
            Str::camel(Str::singular($otherModel)),
            $this->parser->render('morphOne', compact('relationship', 'otherModel', 'OtherModel'))
        );
        $this->addMethodToFile(
            $traitFileInfo,
            Str::camel(Str::singular($otherModel)),
            $this->parser->render('morphOne', compact('relationship', 'otherModel', 'OtherModel'))
        );

        $this->addUseStatementToFile($interfaceFileInfo, $fileInfo);
        $this->addUseStatementToFile($traitFileInfo, $fileInfo);

        $docType = $otherModel;
        $variable = Str::singular(Str::camel($otherModel));
        $this->addClassPropertyDocToFile($interfaceFileInfo, $docType, $variable);
        $this->addClassPropertyDocToFile($traitFileInfo, $docType, $variable);
    }

    public function build()
    {
        return;
    }
}
