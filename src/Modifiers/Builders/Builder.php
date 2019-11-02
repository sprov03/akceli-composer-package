<?php

namespace Akceli\Modifiers\Builders;

use Akceli\Modifiers\ClassModifier;

class Builder extends ClassModifier
{
    private static $builderFactory = [
        'belongsTo' => '\Akceli\Modifiers\Builders\Relationships\BelongsToBuilder',
        'belongsToMany' => '\Akceli\Modifiers\Builders\Relationships\BelongsToManyBuilder',
        'hasOne' => '\Akceli\Modifiers\Builders\Relationships\HasOneBuilder',
        'hasMany' => '\Akceli\Modifiers\Builders\Relationships\HasManyBuilder',
        'morphMany' => '\Akceli\Modifiers\Builders\Relationships\MorphManyBuilder',
        'morphOne' => '\Akceli\Modifiers\Builders\Relationships\MorphOneBuilder',
        'morphTo' => '\Akceli\Modifiers\Builders\Relationships\MorphToBuilder',
        'morphToMany' => '\Akceli\Modifiers\Builders\Relationships\MorphToManyBuilder',
        'trait' => '\Akceli\Modifiers\Builders\Classes\TraitBuilder',
        'interface' => '\Akceli\Modifiers\Builders\Classes\InterfaceBuilder'
    ];

    /**
     * @param $builder_type
     * @param ClassModifier $classModifier
     *
     * @return BuilderInterface
     */
    public static function get($builder_type, ClassModifier $classModifier): BuilderInterface
    {
        $builder = self::$builderFactory[$builder_type];

        return new $builder(
            $classModifier->parser,
            $classModifier->schema,
            $classModifier->force
        );
    }
}
