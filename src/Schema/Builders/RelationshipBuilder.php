<?php

namespace Akceli\Schema\Builders;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RelationshipBuilder
{
    /**
     * @var Blueprint
     */
    private $table;

    /**
     * @var array
     */
    private $relatedTables = [];

    private static $relationshipMaps = [
        'belongsTo' => [
            'hasOne' => 'hasOne',
            'hasMany' => 'hasMany',
        ],
        'morphTo' => [
            'hasOne' => 'morphOne',
            'hasMany' => 'morphMany',
        ]
    ];

    private $relationship;

    /**
     * SchemaBuilder constructor.
     * @param Blueprint $table
     */
    public function __construct(Blueprint $table)
    {
        $this->table = $table;
    }

    /**
     * @param Blueprint $table
     * @return RelationshipBuilder
     */
    public static function table(Blueprint $table)
    {
        return new RelationshipBuilder($table);
    }
    
    public function belongsTo(string $related_table, string $onDelete = 'restrict', bool $nullable = false)
    {
        $related_table = Str::snake($related_table);
        $ref = $this->table->unsignedBigInteger(Str::singular($related_table) . '_id')->index();
        if ($nullable) {
            $ref->nullable();
        }
        $this->table->foreign(Str::singular($related_table) . '_id')->references('id')->on(Str::plural($related_table))->onDelete($onDelete);

        $this->relatedTables = [$related_table];
        $this->relationship = 'belongsTo';
        return $this;
    }
    
    public function belongsToMany(string $table_a, string $table_b, $onDelete = 'restrict')
    {
        $a = Str::singular($table_a);
        $b = Str::singular($table_b);

        $this->table->unsignedBigInteger($a . '_id')->index();
        $this->table->foreign($a . '_id')->references('id')->on($a . 's')->onDelete($onDelete);

        $this->table->unsignedBigInteger($b . '_id')->index();
        $this->table->foreign($b . '_id')->references('id')->on($b . 's')->onDelete($onDelete);

        $this->table->primary([$a . '_id', $b . '_id']);

        $this->relationship = 'belongsToMany';
        $this->relatedTables = [];
        return $this;
    }

    public function morphTo(string $relationship, array $related_tables, int $length = 255, bool $nullable = false)
    {
        $id = $this->table->unsignedBigInteger($relationship . '_id')->index();
        $type = $this->table->string($relationship . '_type', $length)->index();

        if ($nullable) {
            $id->nullable();
            $type->nullable();
        }

        $cacheKey = 'akceli.'.$this->table->getTable().'.morphToRelationships';
        $cache = Cache::get($cacheKey, []);
        array_push($cache, $relationship);
        Cache::put($cacheKey, $cache, 60 * 24 * 30 * 2);

        $this->relationship = 'morphTo';
        $this->relatedTables = $related_tables;
        return $this;
    }

    public function whichHasManyOfThese()
    {
        $hasMany = self::$relationshipMaps[$this->relationship]['hasMany'];
        foreach ($this->relatedTables as $relatedTable) {
            $this->setRelationshipCache($this->table->getTable(), $relatedTable, $hasMany);
        }

        return $this;
    }
    
    public function whichHasOneOfThese()
    {
        $hasOne = self::$relationshipMaps[$this->relationship]['hasOne'];
        foreach ($this->relatedTables as $relatedTable) {
            $this->setRelationshipCache($this->table->getTable(), $relatedTable, $hasOne);
        }

        return $this;
    }

    public function setRelationshipCache($table, $relatedTable, $relationship)
    {
        $cacheKey = 'akceli.relationships.'.$relatedTable;
        $cache = Cache::get($cacheKey, []);
        $cache[$table] = $relationship;
        Cache::put($cacheKey, $cache, 60 * 24 * 30 * 2);

        $this->relatedTable = null;
        return $this;
    }
}
