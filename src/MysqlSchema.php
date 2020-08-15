<?php

namespace Akceli\Schema;

use Akceli\Config\ColumnSettingsConfig;
use Akceli\Console;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MysqlSchema implements SchemaInterface
{
    /** @var  string */
    private $table;
    /** @var  Collection */
    private $compositeKeys;
    /** @var  Collection|MysqlColumn[] */
    private $columns;
    /** @var  Collection|MysqlColumn[] */
    private $primary;

    /**
     * Schema constructor
     *
     * @param string $table
     */
    function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Table name
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return MysqlColumn[]|Collection
     */
    public function getColumns(): Collection
    {
        if (isset($this->columns)) {
            return $this->columns;
        }

        $columns = $this->getTableColumns($this->table);
        $columns = $this->addRules($columns);
        return $columns;
    }

    /**
     * @return Collection|MysqlColumn[]
     */
    public function getPrimaryKey(): Collection
    {
        $primaryKey = new Collection();

        foreach ($this->getColumns() as $column) {
            if ($column->Key == 'PRI') {
                $primaryKey->push($column);
            }
        }

        $this->primary = $primaryKey;

        return $primaryKey;
    }

    /**
     * @return MysqlRelationship[]|Collection
     */
    public function getForeignKeys()
    {
        return $this->getTableForeignKeys();
    }

    public function getPolymorphicRelationships()
    {
        $non_primary_key_columns = $this->getColumns()->filter(function (MysqlColumn $column) {
            return $column->Key !== 'PRI';
        });

        return $this->getInterfaces($non_primary_key_columns);
    }

    /**
     * Belongs to Relationship Consits of a Primary Key of two Coulumns that have Relationships to two other tables.
     *
     * @return Collection
     */
    public function getBelongsToManyRelationships(): Collection
    {
        $primaryKey = $this->getPrimaryKey();

        if ($primaryKey->count() !== 2) {

            return new Collection();
        }


        $relationships = $primaryKey->map(function ($key) {
            return $this->getForeignKeys()
                ->first(function ($foreignKey) use ($key) {
                    return $foreignKey->COLUMN_NAME == $key->Field;
                });
        })->filter(function($relationship) {
            return !!$relationship;
        });

        if ($relationships->count() !== 2) {
            return new Collection();
        }

        return $relationships;
    }

    /**
     * @return Collection
     */
    public function getBelongsToRelationships(): Collection
    {
        /**
         * Filter Other Relationship Types
         */
        $columns = $this->getColumns()->filter(function ($column) {
            return !$this->getBelongsToManyRelationships()->contains('COLUMN_NAME', '=', $column->Field);
        });

        $columns_that_have_relationships =  $columns->filter(function ($key) {
            return (boolean) $this->getForeignKeys()
                ->filter(function ($foreign) use ($key) {
                    return $foreign->COLUMN_NAME == $key->Field;
                })
                ->count();
        });

        return $columns_that_have_relationships->map(function ($column) {
            return $this->getForeignKeys()->firstWhere('COLUMN_NAME', '=', $column->Field);
        });
    }

    public function getPolymorphicManyToManyInterfaces()
    {
        $primaryKey = $this->getPrimaryKey();

        if ($primaryKey->count() !== 4) {
            return [];
        }

        return $this->getInterfaces($primaryKey);
    }

    /**
     * @param Collection|null|MysqlColumn[] $columns
     * @return array
     */
    private function getInterfaces(Collection $columns = null)
    {
        $interface_types = [];
        $interface_ids = [];
        foreach ($columns as $column) {
            if (preg_match('/_type$/', $column->getField())) {
                $interface_types[] = str_replace('_type', '', $column->getField());
            }
            if (preg_match('/_id$/', $column->getField())) {
                $interface_ids[] = str_replace('_id', '', $column->getField());
            }
        }

        return array_intersect($interface_types, $interface_ids);
    }

    /**
     * @param string $table
     *
     * @return Collection|MysqlColumn[]
     *
     * @example
     * {
     *    "Field": "id"
     *    "Type": "int(10) unsigned"
     *    "Null": "NO"
     *    "Key": "PRI"
     *    "Default": null
     *    "Extra": "auto_increment"
     * }
     */
    public function getTableColumns($table): Collection
    {
        $schemInfo = $this->getSchemaInfo($table);
        if ($schemInfo->count() === 0) {
            $wants_to_migrate = Console::choice("The '{$table}' table is not in the database:  Would you like to migrate?", ['yes', 'no'], 'yes');
            if ($wants_to_migrate === 'yes') {
                Artisan::call('migrate');
            } else {
                Console::info('No Files Where Changed');
                die();
            }
        }
        return collect(DB::select("show columns from `{$table}`"))->map(function ($column) use ($schemInfo) {
            $columnInfo = $schemInfo->firstWhere('COLUMN_NAME', '=', $column->Field);
            if ($columnInfo) {
                $column = (object) array_merge((array) $column, (array) $columnInfo);
            }
            return new MysqlColumn($column);
        });
    }

    public function getTableCompositeKeys()
    {
        if (isset($this->compositeKeys)) {
            return $this->compositeKeys;
        }

        return $this->getSchemaInfo($this->table);
    }

    private function getSchemaInfo($table): Collection
    {
        return collect(DB::select(<<<EOF
            SELECT *
            FROM   information_schema.KEY_COLUMN_USAGE
            WHERE  table_name ='{$table}';
EOF
        ));
    }

    /**
     * List of foreign keys for the table
     *
     * @return Collection|MysqlRelationship[]
     *
     * @example
     *  {
     *      "foreign key": "products.user_id",
     *      "references": "users.id"
     *  }
     */
    public function getTableForeignKeys(): Collection
    {
        $database_name = DB::getDatabaseName();

        if (empty($database_name)) {
            Console::info('Cant configure foreign keys till you set your database name in the config file');
        }

        return collect(DB::select(<<<EOF
            select
                *,
                concat(table_name, '.', column_name) as 'foreign_key',  
                concat(referenced_table_name, '.', referenced_column_name) as 'references'
            from
                information_schema.KEY_COLUMN_USAGE
            where
                referenced_table_name is not null
                and table_schema = '$database_name'
                and table_name = '$this->table'
EOF
        ));
    }

    /**
     * @param Collection|MysqlColumn[] $columns
     * @return Collection
     */
    public function addRules(Collection $columns)
    {
        $ignore_patterns = [
            '^id$',
//            '^created_at$',
//            '^updated_at$',
//            '^deleted_at$'
        ];

        foreach ($columns as $column) {
            if (count($ignore_patterns)) {
                if (preg_match("/" . implode('|', $ignore_patterns) . "/", $column->getField())) {
                    continue;
                }
            }

            if ($column->isNullable()) {
                $column->rules = 'nullable|';
            } else {
                $column->rules = 'required|';
            }

            if ($column->isTimeStamp()) {
                $column->rules .= "date|";
            }

            if ($column->isInteger()) {
                $column->rules .= "integer|";
            }

            if ($column->isEnum()) {
                $string = $column->Type;
                $string = substr($string, 6);
                $string = substr($string, 0, strlen($string) -2);
                $types = implode(',', explode("','", $string));

                $column->rules .= "in:{$types}|";
            }

            if ($column->isBoolean()) {
                $column->rules .= "boolean|";
            }

            if ($column->isString()) {
                if (preg_match('/(var)?char\((\d*)\)/', $column->Type, $max)) {
                    $column->rules .= "max:{$max[2]}|";
                }
            }

            if ($this->isUnique($column)) {
                $column->rules .= "unique:{$this->table},{$column->Field}|";
            }

            $column->rules = rtrim($column->rules, '|');
        }

        return $columns;
    }

    public function getConfigValue(MysqlColumn $column, ColumnSettingsConfig $config)
    {
        if (count($config->ignorePatterns)) {
            if (preg_match("/" . implode('|', $config->ignorePatterns) . "/", $column->Field)) {
                return null;
            }
        }

        if ($column->isInteger()) return $config->integer;
        if ($column->isString()) return $config->string;
        if ($column->isEnum()) return $config->enum;
        if ($column->isTimeStamp()) return $config->timestamp;
        if ($column->isBoolean()) return $config->boolean;

        Console::info(
            "Column info not identified for: {$column->Type} " .
            json_encode($column, JSON_PRETTY_PRINT)
        );

        return null;
    }

    public function isUnique($column)
    {
        return (bool) $this->getTableCompositeKeys()
            ->where('CONSTRAINT_NAME', '=', "{$this->table}_{$column->Field}_unique")
            ->first();
    }

    public function isCompositeKey($column)
    {
        return !! $this->getCompositeKey($column);
    }

    public function getCompositeKey($column)
    {
        $composite_keys = $this->getTableCompositeKeys()
            ->filter(function ($key) use ($column) {
                return ! str_contains($key->CONSTRAINT_NAME, ['_unique', '_foreign', 'PRIMARY']);
            });

        $composite_key = $composite_keys
            ->where('CONSTRAINT_NAME', '=', $column->Field)
            ->first();

        return $composite_key ? $composite_key : $composite_keys
            ->where('COLUMN_NAME', '=', $column->Field)
            ->where('REFERENCED_TABLE_NAME', '=', null)
            ->first();
    }
}
