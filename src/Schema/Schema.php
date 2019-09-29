<?php

namespace Akceli\Schema;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Schema
{
    /** @var  string */
    private $table;
    /** @var  Collection */
    private $compositeKeys;
    /** @var  Collection|Column[] */
    private $columns;
    /** @var  Collection|Column[] */
    private $primary;

    /**
     * Schema constructor
     *
     * @param string $table
     */
    function __construct($table)
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
     * @return Column[]|Collection
     */
    public function getColumns()
    {
        if (isset($this->columns)) {
            return $this->columns;
        }

        return $this->processColumns();
    }

    /**
     * @return Collection|Column[]
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
     * @return Relationship[]|Collection
     */
    public function getForeignKeys()
    {
        return $this->getTableForeignKeys();
    }

    public function getPolymorphicRelationships()
    {
        $non_primary_key_columns = $this->getColumns()->filter(function ($column) {
            return $column->Key !== 'PRI';
        });

        return $this->getInterfaces($non_primary_key_columns);
    }

    public function getBelongsToManyRelationships()
    {
        $primaryKey = $this->getPrimaryKey();

        if ($primaryKey->count() !== 2) {

            return new Collection();
        }

        return $primaryKey->map(function ($key) {
            return $this->getForeignKeys()
                ->first(function ($index, $value) use ($key) {
                    return $value->COLUMN_NAME == $key->Field;
                });
        });
    }

    public function getBelongsToRelationships()
    {
        $columns_that_have_relationships =  $this->getColumns()->filter(function ($key) {
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

    /**
     *
     * @return array
     */
    public function getPolymorphicManyToManyInterfaces()
    {
        $primaryKey = $this->getPrimaryKey();

        if ($primaryKey->count() !== 4) {
            return [];
        }

        return $this->getInterfaces($primaryKey);
    }

    /**
     * @param Collection|null|Column[] $columns
     * @return array
     */
    private function getInterfaces(Collection $columns = null)
    {
        $interface_types = [];
        $interface_ids = [];
        foreach ($columns as $column) {
            if (preg_match('/_type$/', $column->Field)) {
                $interface_types[] = str_replace('_type', '', $column->Field);
            }
            if (preg_match('/_id$/', $column->Field)) {
                $interface_ids[] = str_replace('_id', '', $column->Field);
            }
        }

        return array_intersect($interface_types, $interface_ids);
    }

    /**
     * @return Collection|Column[]
     */
    private function processColumns(): Collection
    {
        $columns = $this->getTableColumns($this->table);
        $columns = $this->addClassDocs($columns);
        $columns = $this->addRules($columns);
        $columns = $this->addCasts($columns);

        return $columns;
    }

    /**
     * @param string $table
     *
     * @return Collection
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
    public function getTableColumns($table)
    {
        return collect(DB::select("show columns from " . $table));
    }

    public function getTableCompositeKeys()
    {
        if (isset($this->compositeKeys)) {
            return $this->compositeKeys;
        }

        return collect(DB::select(<<<EOF
            SELECT * 
            FROM   information_schema.KEY_COLUMN_USAGE     
            WHERE  table_name ='$this->table';
EOF
        ));
    }

    /**
     * List of foreign keys for the table
     *
     * @return Collection|Relationship[]
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

    public function addRules(Collection $columns)
    {
        $ignore_patterns = [
//            '^id$',
//            '^created_at$',
//            '^updated_at$',
//            '^deleted_at$'
        ];

        foreach ($columns as $column) {
            if (preg_match("/" . implode('|', $ignore_patterns) . "/", $column->Field)) {
                continue;
            }

            $column->rules = 'required|';

            if ($this->isTimeStamp($column)) {
                $column->rules .= "date|";
            }

            if ($this->isInteger($column)) {
                $column->rules .= "integer|";
            }

            if ($this->isEnum($column)) {
                $string = $column->Type;
                $string = substr($string, 6);
                $string = substr($string, 0, strlen($string) -2);
                $types = implode(',', explode("','", $string));

                $column->rules .= "in:{$types}|";
            }

            if ($this->isBoolean($column)) {
                $column->rules .= "boolean|";
            }

            if (preg_match('/varchar\((\d*)\)/', $column->Type, $max)) {
                $column->rules .= "max:{$max[1]}|";
            }

            if ($this->isUnique($column)) {
                $column->rules .= "unique:{$this->table},{$column->Field}|";
            }

            $column->rules = rtrim($column->rules, '|');
        }

        return $columns;
    }

    public function addClassDocs(Collection $columns)
    {
        foreach ($columns as $column) {
            $column->name = $column->Field;
            $column->display = ucfirst(str_replace('_', ' ', $column->Field));

            if ($column->Field === 'id') {
                $column->type = 'id';
            }

            if ($this->isInteger($column)) {
                $column->document_type = 'integer';
                $column->type = 'number';
                continue;
            }

            if ($this->isString($column) || $this->isEnum($column)) {
                $column->document_type = 'string';
                $column->type = 'text';
                continue;
            }

            if ($this->isTimeStamp($column)) {
                $column->document_type = '\Carbon\Carbon';
                $column->type = 'timestamp';
                continue;
            }

            if ($this->isBoolean($column)) {
                $column->document_type = 'boolean';
                $column->type = 'boolean';
                continue;
            }

            Console::info(
                "Field Doc not yet implemented for this type: {$column->Type}\n" .
                json_encode($column, JSON_PRETTY_PRINT)
            );
        }

        return $columns;
    }

    public function addCasts(Collection $columns)
    {
        $ignore_patterns = [
            '^created_at$',
            '^updated_at$',
            '^deleted_at$'
        ];

        foreach ($columns as $column) {
            if (preg_match("/" . implode('|', $ignore_patterns) . "/", $column->Field)) {
                continue;
            }
            if ($this->isInteger($column)) {
                continue;
            }
            if ($this->isString($column)) {
                continue;
            }

            if ($this->isEnum($column)) {
                continue;
            }

            if ($this->isTimeStamp($column)) {
                $column->casts = '\Carbon\Carbon';
                continue;
            }

            if ($this->isBoolean($column)) {
                $column->casts = 'boolean';
                continue;
            }

            Console::info(
                "Field Cast not yet implemented for this type: {$column->Type} " .
                json_encode($column, JSON_PRETTY_PRINT)
            );
        }

        return $columns;

    }

    public function isInteger($column)
    {
        return preg_match('/^(big)?int\((\d*)\)/', $column->Type);
    }

    public function isBoolean($column)
    {
        return preg_match('/^tinyint\((\d*)\)/', $column->Type);
    }

    public function isTimeStamp($column)
    {
        return preg_match('/^timestamp$/', $column->Type);
    }

    public function isEnum($column)
    {
        return str_contains($column->Type, 'enum(');
    }

    public function isString($column)
    {
        return preg_match('/^varchar\((\d*)\)$|^text$/', $column->Type);
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
