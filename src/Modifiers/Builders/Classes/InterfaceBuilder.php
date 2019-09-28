<?php

namespace Akceli\Modifiers\Builders\Classes;

use Akceli\FileService;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\Modifiers\ClassModifier;

class InterfaceBuilder extends ClassModifier implements BuilderInterface
{
    public function analise($relationship, $interface = null)
    {
        $file = new FileService(app_path());

        $fileInfo = $file->findByTableName($this->schema->getTable());

        $interfaceFileInfo = $file->findByClassName("{$interface}Interface");

        if (isset($interfaceFileInfo)) {
            $this->output->warn("{$interface}Interface.php already exists.");

            return;
        }

        $this->putFile(
            $this->parser->render('interface', compact('interface')),
            str_replace($fileInfo->getFilename(), "{$interface}Interface.php", $fileInfo->getRealPath())
        );
    }
}
