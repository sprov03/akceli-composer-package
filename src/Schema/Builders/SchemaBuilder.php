<?php

namespace Akceli\Schema\Builders;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SchemaBuilder
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
     * @return SchemaBuilder
     */
    public static function table(Blueprint $table)
    {
        return new SchemaBuilder($table);
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
