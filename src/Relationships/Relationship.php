<?php

namespace Akceli\Schema\Relationships;

use Illuminate\Database\Eloquent\Model;

class Relationship
{
    public static function belongsTo(Model $relatedModel, string $onDelete = 'RESTRICT'): BelongsToRelationship
    {
        return new BelongsToRelationship($relatedModel, $onDelete);
    }
}