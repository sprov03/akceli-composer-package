<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;

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
    public function updateFiles(
        \SplFileInfo $fileInfo,
        \SplFileInfo $interfaceFileInfo,
        \SplFileInfo $traitFileInfo,
        $interface,
        $relationship
    ) {
        $interface = Str::studly($interface);
        $otherModel = Str::singular(Str::studly($this->schema->getTable()));

        $this->addAbstractMethodToFile(
            $interfaceFileInfo,
            Str::camel(Str::singular($otherModel)),
            $this->parser->render('morphOne', compact('relationship', 'interface', 'otherModel'))
        );
        $this->addMethodToFile(
            $traitFileInfo,
            Str::camel(Str::singular($otherModel)),
            $this->parser->render('morphOne', compact('relationship', 'interface', 'otherModel'))
        );

        $this->addUseStatementToFile($interfaceFileInfo, $fileInfo);
        $this->addUseStatementToFile($traitFileInfo, $fileInfo);

        $docType = $otherModel;
        $variable = Str::singular(Str::camel($otherModel));
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
}
