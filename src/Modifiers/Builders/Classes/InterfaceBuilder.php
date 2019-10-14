<?php

namespace Akceli\Modifiers\Builders\Classes;

use Akceli\FileService;
use Akceli\Console;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\Modifiers\ClassModifier;

class InterfaceBuilder extends ClassModifier implements BuilderInterface
{
    public function analise($relationship, $interface = null)
    {
        $fileInfo = FileService::findByTableName($this->schema->getTable());
        $interfaceFileInfo = FileService::findByClassName("{$interface}Interface");

        if (isset($interfaceFileInfo)) {
            Console::warn("{$interface}Interface.php (Already Exists)");

            return;
        }

        $this->putFile(
            $this->parser->render('interface', compact('interface')),
            str_replace($fileInfo->getFilename(), "{$interface}Interface.php", $fileInfo->getRealPath())
        );
    }
}
