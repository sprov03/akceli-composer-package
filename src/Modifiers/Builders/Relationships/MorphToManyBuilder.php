<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\Console;
use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;

class MorphToManyBuilder extends Builder implements BuilderInterface
{
    public function analise($relationship, $interface = null)
    {
        $fileInfo = FileService::findByTableName($this->schema->getTable());
        $interfaceFileInfo = FileService::findByClassName($interface . 'Interface');

        $tables = Console::ask("List tables that this references as a scv \n example users,sites,dogs");

        foreach (explode(',', $tables) as $table) {
            $otherModel = str_replace('.php', '', FileService::findByTableName($table)->getFilename());

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
