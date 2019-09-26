<?php

namespace CrudGenerator\Modifiers\Builders\Relationships;

use CrudGenerator\File;
use CrudGenerator\Modifiers\Builders\Builder;
use CrudGenerator\Modifiers\Builders\BuilderInterface;

class MorphToManyBuilder extends Builder implements BuilderInterface
{
    public function analise($relationship, $interface = null)
    {
        $file = new File(app_path());

        $fileInfo = $file->findByTableName($this->schema->getTable());
        $interfaceFileInfo = $file->findByClassName($interface . 'Interface');

        $tables = $this->output->ask("List tables that this references as a scv \n example users,sites,dogs");

        foreach (explode(',', $tables) as $table) {
            $otherModel = str_replace('.php', '', $this->files->findByTableName($table)->getFilename());

            $this->addMethodToFile(
                $fileInfo,
                str_plural(camel_case($otherModel)),
                $this->parser->render('morphToMany', compact('interface', 'otherModel'))
            );

            $this->addClassPropertyDocToFile(
                $fileInfo,
                "{$interface}Interface[]|\\Illuminate\\Database\\Eloquent\\Collection",
                str_plural(camel_case($otherModel))
            );
        }

        $this->addUseStatementToFile($fileInfo, $interfaceFileInfo);
    }
}
