<?php

namespace Akceli\Schema\Builders;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class SchemaBuilder
{
    /**
     * @var Blueprint
     */
    private $table;

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

        return $this;
    }
}
