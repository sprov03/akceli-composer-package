<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        $this->addClassPropertyDocToFile($fileInfo, $interface, Str::camel($relationship));
    }

    public function build(bool $noInteraction = false)
    {
        $cacheKey = 'akceli.'.$this->schema->getTable().'.morphToRelationships';
        if (!Cache::has($cacheKey)) {
            return;
        }

        $relationships = Cache::get($cacheKey);
        foreach ($relationships as $relationship) {
            $interface = Str::studly($relationship) . 'Interface';
            $fileInfo = FileService::findByTableName($this->schema->getTable());
            $interfaceFileInfo = FileService::findByClassName($interface);
            if (!$interfaceFileInfo) {
                $fileContent = $this->parser->render('interface', compact('interface'));
                FileService::putFile('app/Interfaces/'.$interface.'.php', $fileContent);
                $interfaceFileInfo = FileService::findByClassName($interface, true);
            }

            $this->updateFiles($fileInfo, $interfaceFileInfo, $interface, $relationship);
        }
    }

    public function buildRelated()
    {
        return;
    }
}
