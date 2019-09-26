<?php

namespace CrudGenerator\Modifiers\Builders\Classes;

use CrudGenerator\File;
use CrudGenerator\Modifiers\Builders\BuilderInterface;
use CrudGenerator\Modifiers\ClassModifier;

class TraitBuilder extends ClassModifier implements BuilderInterface
{
    public function analise($relationship, $interface = null)
    {
        $file = new File(app_path());

        $fileInfo = $file->findByTableName($this->schema->getTable());

        $traitFileInfo = $file->findByClassName("{$interface}Trait");

        if (isset($traitFileInfo)) {
            $this->output->warn("{$interface}Trait.php already exists.");

            return;
        }

        $this->putFile(
            $this->parser->render('trait', compact('interface')),
            str_replace($fileInfo->getFilename(), "{$interface}Trait.php", $fileInfo->getRealPath())
        );
    }
}
