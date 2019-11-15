<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Illuminate\Support\Str;

class MorphManyBuilder extends Builder implements BuilderInterface
{
    /**
     * Build sections of files and place them in the files
     *
     * @param \SplFileInfo $fileInfo
     * @param \SplFileInfo $interfaceFileInfo
     * @param \SplFileInfo $traitFileInfo
     * @param $interface
     *
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
        $otherModels = Str::plural($otherModel);

        $this->addAbstractMethodToFile(
            $interfaceFileInfo,
            $otherModels,
            $this->parser->render('morphMany', compact('relationship', 'otherModels', 'OtherModel'))
        );
        $this->addMethodToFile(
            $traitFileInfo,
            $otherModels,
            $this->parser->render('morphMany', compact('relationship', 'otherModels', 'OtherModel'))
        );

        $this->addUseStatementToFile($interfaceFileInfo, $fileInfo);
        $this->addUseStatementToFile($traitFileInfo, $fileInfo);

        $docType = "{$otherModel}[]|\\Illuminate\\Database\\Eloquent\\Collection";
        $variable = Str::plural(Str::camel($otherModel));
        $this->addClassPropertyDocToFile($interfaceFileInfo, $docType, $variable);
        $this->addClassPropertyDocToFile($traitFileInfo, $docType, $variable);
    }

    public function analise($relationship, $interface = null)
    {
        $fileInfo = FileService::findByTableName($this->schema->getTable());
        $interfaceFileInfo = FileService::findByClassName($interface . 'Interface');
        $traitFileInfo = FileService::findByClassName($interface . 'Trait');

        $this->updateFiles($fileInfo, $interfaceFileInfo, $traitFileInfo, $interface, $relationship);
    }

    public function build()
    {
        return;
    }
}
