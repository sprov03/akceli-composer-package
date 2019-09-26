<?php

namespace CrudGenerator\Modifiers\Builders\Classes;

use CrudGenerator\File;
use CrudGenerator\Modifiers\Builders\BuilderInterface;
use CrudGenerator\Modifiers\ClassModifier;

class InterfaceBuilder extends ClassModifier implements BuilderInterface
{
    public function analise($relationship, $interface = null)
    {
        $file = new File(app_path());

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
