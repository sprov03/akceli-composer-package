<?php

namespace Akceli\Modifiers\Builders\Relationships;

use Akceli\Console;
use Akceli\FileService;
use Akceli\Modifiers\Builders\Builder;
use Akceli\Modifiers\Builders\BuilderInterface;
use Akceli\GeneratorService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BelongsToBuilder extends Builder implements BuilderInterface
{
    public function build(bool $noInteraction = false)
    {
        foreach ($this->schema->getBelongsToRelationships() as $relationship) {
            /**
             * Initalize Data
             */
            $otherFile = FileService::findByTableName($relationship->REFERENCED_TABLE_NAME);
            if (!$otherFile) {
                Artisan::call('akceli:generate model ' . $relationship->REFERENCED_TABLE_NAME);
                $otherFile = FileService::findByTableName($relationship->REFERENCED_TABLE_NAME, true);
            }
            $otherModel = FileService::getClassNameOfFile($otherFile);
            $thisModel = FileService::getClassNameOfFile($this->fileInfo);
            $templateData = [
                'relationship' => $relationship,
                'otherModel' => $otherModel,
                'belongsToMethodName' => Str::camel($otherModel)
            ];

            /**
             * Update Files
             */
            $this->addUseStatementToFile($this->fileInfo, $otherFile);
            $this->addClassPropertyDocToFile($this->fileInfo, $otherModel, Str::camel($otherModel));
            $this->addMethodToFile($this->fileInfo, Str::camel($otherModel), $this->parser->render('belongsTo', $templateData));

            $cacheKey = 'akceli.relationships.'.$relationship->REFERENCED_TABLE_NAME;
            if (Cache::has($cacheKey)) {
                $cache = Cache::get($cacheKey);
                if ($builder = $cache[$this->schema->getTable()] ?? null) {
                    $this->getBuilder($builder)->buildRelated($relationship);
                    continue;
                }
            } elseif ($noInteraction) {
                continue;
            }
            
            /**
             * Build Related
             */
            $choice = Console::choice(
                "Dose a {$otherModel} have one or many " . Str::plural($thisModel) . "?",
                [
                    "0: Don't Set Up the other relationship",
                    "1: {$otherModel} has one {$thisModel}",
                    "2: {$otherModel} has many " . Str::plural($thisModel),
                ]
            );

            if ($choice == 1) {
                $builder = $this->builder_map['hasOne'];
            } elseif ($choice == 2) {
                $builder = $this->builder_map['hasMany'];
            }


            $this->getBuilder($builder)->buildRelated($relationship);
        }
    }
    
    public function buildRelated()
    {
        return;
    }
}
