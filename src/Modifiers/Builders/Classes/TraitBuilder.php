<?php

namespace Akceli\Modifiers\Builders\Classes;

use Akceli\FileService;
use Akceli\Console;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\Modifiers\ClassModifier;

class TraitBuilder extends ClassModifier implements BuilderInterface
{
    public function analise($relationship, $interface = null)
    {
        $fileInfo = FileService::findByTableName($this->schema->getTable());
        $traitFileInfo = FileService::findByClassName("{$interface}Trait");

        if (isset($traitFileInfo)) {
           Console::warn("{$interface}Trait.php already exists.");

            return;
        }

        $this->putFile(
            $this->parser->render('trait', compact('interface')),
            str_replace($fileInfo->getFilename(), "{$interface}Trait.php", $fileInfo->getRealPath())
        );
    }
}
