<?php

namespace CrudGenerator\Modifiers\Builders\Relationships;

use CrudGenerator\File;
use CrudGenerator\Modifiers\Builders\Builder;
use CrudGenerator\Modifiers\Builders\BuilderInterface;
use CrudGenerator\Modifiers\ClassModifier;

class MorphToBuilder extends Builder implements BuilderInterface
{
    /**
     * Build sections of files and place them in the files
     *
     * @param \SplFileInfo $fileInfo
     * @param \SplFileInfo $interfaceFileInfo
     * @param $interface
     * @param $relationship
     *
     * @return void
     */
    public function updateFiles(
        \SplFileInfo $fileInfo,
        \SplFileInfo $interfaceFileInfo,
        $interface,
        $relationship
    ) {
        $this->addMethodToFile(
            $fileInfo,
            camel_case(str_singular($relationship)),
            $this->parser->render('morphTo', compact('interface', 'relationship'))
        );

        $this->addUseStatementToFile($fileInfo, $interfaceFileInfo);
        $this->addClassPropertyDocToFile($fileInfo, "{$interface}Interface", camel_case($relationship));
    }

    public function analise($relationship, $interface = null)
    {
        $file = new File(app_path());

        $fileInfo = $file->findByTableName($this->schema->getTable());
        $interfaceFileInfo = $file->findByClassName($interface . 'Interface');

        $this->updateFiles($fileInfo, $interfaceFileInfo, $interface, $relationship);
    }
}
