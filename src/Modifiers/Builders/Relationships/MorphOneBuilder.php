<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\Modifiers\ClassModifier;

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
        $interface = studly_case($interface);
        $otherModel = str_singular(studly_case($this->schema->getTable()));

        $this->addAbstractMethodToFile(
            $interfaceFileInfo,
            camel_case(str_singular($otherModel)),
            $this->parser->render('morphOne', compact('relationship', 'interface', 'otherModel'))
        );
        $this->addMethodToFile(
            $traitFileInfo,
            camel_case(str_singular($otherModel)),
            $this->parser->render('morphOne', compact('relationship', 'interface', 'otherModel'))
        );

        $this->addUseStatementToFile($interfaceFileInfo, $fileInfo);
        $this->addUseStatementToFile($traitFileInfo, $fileInfo);

        $docType = $otherModel;
        $variable = str_singular(camel_case($otherModel));
        $this->addClassPropertyDocToFile($interfaceFileInfo, $docType, $variable);
        $this->addClassPropertyDocToFile($traitFileInfo, $docType, $variable);
    }

    public function analise($relationship, $interface = null)
    {
        $file = new FileService(app_path());

        $fileInfo = $file->findByTableName($this->schema->getTable());
        $interfaceFileInfo = $file->findByClassName($interface . 'Interface');
        $traitFileInfo = $file->findByClassName($interface . 'Trait');

        $this->updateFiles($fileInfo, $interfaceFileInfo, $traitFileInfo, $interface, $relationship);
    }
}
