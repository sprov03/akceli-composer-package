<?php

namespace Akceli\Schema\Relationships;

use Akceli\Schema\Columns\Column;
use Akceli\Schema\Items;
use App\Models\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;

class Relationship
{
    public static function belongsTo(Model $relatedModel, string $onDelete = 'RESTRICT'): BelongsToRelationship
    {
        $relationship = new BelongsToRelationship($relatedModel, $onDelete);
        return $relationship;
    }
}