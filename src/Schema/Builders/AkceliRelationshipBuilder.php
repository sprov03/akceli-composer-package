<?php

namespace Akceli\Schema\Builders;

use Akceli\Console;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AkceliRelationshipBuilder
{
    /**
     * @var Blueprint
     */
    private $table;

    private $relatedTable = null;
    private $cacheKey = null;
    private $temp = null;
    private $relationship = null;

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
     * @return AkceliRelationshipBuilder
     */
    public static function table(Blueprint $table)
    {
        return new AkceliRelationshipBuilder($table);
    }

    public function belongsTo(string $related_table, string $onDelete = 'restrict', bool $nullable = false, string $relationship_name = null)
    {
        $related_table = Str::snake($related_table);
        $relationship_name = ($relationship_name) ?? Str::singular($related_table);
        $ref = $this->table->unsignedBigInteger($relationship_name . '_id')->index();
        if ($nullable) {
            $ref->nullable();
        }
        $this->table->foreign($relationship_name . '_id')->references('id')->on(Str::plural($related_table))->onDelete($onDelete);

        $this->cacheKey = null;
        $this->temp = null;
        $this->relatedTable = $related_table;
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

        $this->cacheKey = null;
        $this->temp = null;
        $this->relationship = 'belongsToMany';
        $this->relatedTable = null;

        return $this;
    }

    public function morphTo(string $relationship, int $length = 255, bool $nullable = false)
    {
        $id = $this->table->unsignedBigInteger($relationship . '_id')->index();
        $type = $this->table->string($relationship . '_type', $length)->index();

        if ($nullable) {
            $id->nullable();
            $type->nullable();
        }

        $this->cacheKey = 'akceli.'.$this->table->getTable().'.morphToRelationships';
        $this->temp = $relationship;
        $this->relationship = 'morphTo';
        $this->relatedTable = null;

        return $this;
    }

    public function whichHasManyOfThese(string $relationshipName = null)
    {
        if ($this->relationship === 'morphTo') {
            $cache = $this->getCache();
            $cache[$this->temp] = [
                'relationshipName' => $this->type,
                'reverseRelationshipName' => $relationshipName,
                'relationshipType' => 'morphMany',
            ];
            $this->setCache($cache);
        } elseif ($this->relationship === 'belongsTo') {
            $this->setBelongsToRelationshipCache($this->table->getTable(), $this->relatedTable, 'hasMany', $relationshipName);
        }


        return $this;
    }

    public function whichHasOneOfThese(string $relationshipName = null)
    {
        if ($this->relationship === 'morphTo') {
            $cache = $this->getCache();
            $cache[$this->temp] = [
                'relationshipName' => $this->type,
                'reverseRelationshipName' => $relationshipName,
                'relationshipType' => 'morphOne',
            ];
            $this->setCache($cache);
        } elseif ($this->relationship === 'belongsTo') {
            $this->setBelongsToRelationshipCache($this->table->getTable(), $this->relatedTable, 'hasOne', $relationshipName);
        }

        return $this;
    }

    public function setBelongsToRelationshipCache($table, $relatedTable, $relationshipType, $relationshipName)
    {
        $cacheKey = 'akceli.relationships.'.$relatedTable;
        $cache = Cache::get($cacheKey, []);
        $cache[$table] = $relationshipType;
        $cache['relationshipName'] = $relationshipName;
        Cache::put($cacheKey, $cache, 60 * 24 * 30 * 2);

        $this->relatedTable = null;
        return $this;
    }

    private function getCache()
    {
        return Cache::get($this->cacheKey, []);
    }

    private function setCache($cache)
    {
        Cache::put($this->cacheKey, $cache, 60 * 24 * 30 * 2);
    }
}
