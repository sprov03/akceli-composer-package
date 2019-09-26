<?php

namespace CrudGenerator\Modifiers\Builders;

use CrudGenerator\Modifiers\ClassModifier;
use CrudGenerator\Service;

class Builder extends ClassModifier
{
    private static $builderFactory = [
        'BelongsTo' => '\CrudGenerator\Modifiers\Builders\Relationships\BelongsToBuilder',
        'BelongsToMany' => '\CrudGenerator\Modifiers\Builders\Relationships\BelongsToManyBuilder',
        'HasOne' => '\CrudGenerator\Modifiers\Builders\Relationships\HasOneBuilder',
        'HasMany' => '\CrudGenerator\Modifiers\Builders\Relationships\HasManyBuilder',
        'MorphMany' => '\CrudGenerator\Modifiers\Builders\Relationships\MorphManyBuilder',
        'MorphOne' => '\CrudGenerator\Modifiers\Builders\Relationships\MorphOneBuilder',
        'MorphTo' => '\CrudGenerator\Modifiers\Builders\Relationships\MorphToBuilder',
        'MorphToMany' => '\CrudGenerator\Modifiers\Builders\Relationships\MorphToManyBuilder',
        'Trait' => '\CrudGenerator\Modifiers\Builders\Classes\TraitBuilder',
        'Interface' => '\CrudGenerator\Modifiers\Builders\Classes\InterfaceBuilder'
    ];

    /**
     * @param $builder_type
     * @param ClassModifier $classModifier
     *
     * @return BuilderInterface
     */
    public static function get($builder_type, ClassModifier $classModifier)
    {
        $builder = self::$builderFactory[$builder_type];

        return new $builder(
            $classModifier->parser,
            $classModifier->schema,
            $classModifier->output,
            $classModifier->force
        );
    }

    public function newGenerator($table) {
        $generator = new Service(
            $table,
            studly_case(str_singular($table)),
            $GLOBALS['options'],
            $this->output
        );

        $generator->Generate($GLOBALS['template_set'], false, false, true, true);
    }
}
