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
     * @var string
     */
    private $relatedTable;

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
    
    public function belongsTo(string $model, string $onDelete = 'restrict', bool $nullable = false)
    {
        $model = Str::snake($model);
        $ref = $this->table->unsignedBigInteger(Str::singular($model) . '_id')->index();
        if ($nullable) {
            $ref->nullable();
        }
        $this->table->foreign(Str::singular($model) . '_id')->references('id')->on(Str::plural($model))->onDelete($onDelete);

        $this->relatedTable = $model;
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
        
        return $this;
    }

    public function whichHasManyOfThese()
    {
        return $this->setRelationshipCache($this->table->getTable(), $this->relatedTable, 'hasMany');
    }
    
    public function whichHasOneOfThese()
    {
        return $this->setRelationshipCache($this->table->getTable(), $this->relatedTable, 'hasOne');
    }
    
    private function setRelationshipCache($table, $relatedTable, $relationship)
    {
        $cacheKey = 'akceli.relationships.'.$relatedTable;
        if (Cache::has($cacheKey)) {
            $cache = Cache::get($cacheKey);
        } else {
            $cache = [];
        }
        $cache[$table] = $relationship;
        Cache::put($cacheKey, $cache, 60 * 24 * 30 * 2);

        $this->relatedTable = null;
        return $this;
    }
}
