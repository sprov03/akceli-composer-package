<?php

namespace Akceli\Modifiers\Builders;

use Akceli\Modifiers\ClassModifier;

class Builder extends ClassModifier
{
    private static $builderFactory = [
        'BelongsTo' => '\Akceli\Modifiers\Builders\Relationships\BelongsToBuilder',
        'BelongsToMany' => '\Akceli\Modifiers\Builders\Relationships\BelongsToManyBuilder',
        'HasOne' => '\Akceli\Modifiers\Builders\Relationships\HasOneBuilder',
        'HasMany' => '\Akceli\Modifiers\Builders\Relationships\HasManyBuilder',
        'MorphMany' => '\Akceli\Modifiers\Builders\Relationships\MorphManyBuilder',
        'MorphOne' => '\Akceli\Modifiers\Builders\Relationships\MorphOneBuilder',
        'MorphTo' => '\Akceli\Modifiers\Builders\Relationships\MorphToBuilder',
        'MorphToMany' => '\Akceli\Modifiers\Builders\Relationships\MorphToManyBuilder',
        'Trait' => '\Akceli\Modifiers\Builders\Classes\TraitBuilder',
        'Interface' => '\Akceli\Modifiers\Builders\Classes\InterfaceBuilder'
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
}
