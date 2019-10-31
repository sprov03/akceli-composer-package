<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;

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
            Str::camel(Str::singular($relationship)),
            $this->parser->render('morphTo', compact('interface', 'relationship'))
        );

        $this->addUseStatementToFile($fileInfo, $interfaceFileInfo);
        $this->addClassPropertyDocToFile($fileInfo, "{$interface}Interface", Str::camel($relationship));
    }

    public function analise($relationship, $interface = null)
    {
        $fileInfo = FileService::findByTableName($this->schema->getTable());
        $interfaceFileInfo = FileService::findByClassName($interface . 'Interface');

        $this->updateFiles($fileInfo, $interfaceFileInfo, $interface, $relationship);
    }
}
