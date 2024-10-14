<?php

namespace Akceli\Schema\Relationships;

<<<<<<< HEAD:src/Schema/Relationships/Relationship.php
use Akceli\Schema\Columns\Column;
use Akceli\Schema\Items;
use App\Models\BaseModelTrait;
=======
>>>>>>> 76ab71607108c1c2017c88016bd634106f237054:src/Relationships/Relationship.php
use Illuminate\Database\Eloquent\Model;

class Relationship
{
    public static function belongsTo(Model $relatedModel, string $onDelete = 'RESTRICT'): BelongsToRelationship
    {
        return new BelongsToRelationship($relatedModel, $onDelete);
    }
}