<?php

namespace Akceli\Schema\Columns;

use Akceli\Schema\Items;
use Akceli\Schema\SchemaInterface;
use Akceli\Schema\SchemaItemInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class Column
 * @package Akceli\Schema\Columns
 * @mixin Blueprint
 */
class Column implements SchemaItemInterface
{
    public bool $is_nullable = false;
    public bool $is_unique = false;
    public $default;

    public string $column_type;
    public string $column_name;
    public array $column_args = [];
    /** @var array|MigrationMethod[]  $migration_methods */
    public array $migration_methods = [];
    public string $cast_to = 'string';
    public string $data_type = 'string';
    public array $validators = [];
    public array $create_validators = [];
    public array $update_validators = [];
    
    public function __construct(
        array $migration_methods = [],
        string $cast_to = 'string',
        string $data_type = 'string',
        array $validators = [],
        array $create_validators = [],
        array $update_validators = []
    ) {
        $this->migration_methods = $migration_methods;
        $this->cast_to = $cast_to;
        $this->data_type = $data_type;
        $this->validators = $validators;
        $this->create_validators = $create_validators;
        $this->update_validators = $update_validators;
    }

    public static function new(
       array $migration_methods = [],
       string $cast_to = 'string',
       string $data_type = 'string',
       array $validators = [],
       array $create_validators = [],
       array $update_validators = []
    ) {
        return new static(
            $migration_methods,
            $cast_to,
            $data_type,
            $validators,
            $create_validators,
            $update_validators
        );
    }
    
    public static function id()
    {
        return new static(
            [new MigrationMethod('id')],
            'integer',
            'int',
            [],
            [],
            []
        );
    }

    public static function uuid()
    {
        return new static(
            [new MigrationMethod('uuid')],
            'string',
            'string',
            [],
            [],
            []
        );
    }

    public static function string(int $length = 255)
    {
        return new static(
            [new MigrationMethod('string', [$length])],
            'string',
            'string',
            ['max:' . $length],
            [],
            []
        );
    }

    public static function foreign(Model $relatedModel)
    {
        return new static(
            [new MigrationMethod('foreign')],
            'integer',
            'int',
            [],
            [],
            []
        );
    }

    public static function tinyInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('tinyInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:127', 'min:-128'],
            [],
            []
        );
    }

    public static function smallInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('smallInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:32767', 'min:-32768'],
            [],
            []
        );
    }

    public static function mediumInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('mediumInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:8388607', 'min:-8388608'],
            [],
            []
        );
    }

    public static function integer(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('integer', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:2147483647', 'min:-2147483648'],
            [],
            []
        );
    }

    public static function bigInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('bigInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer'],
            [],
            []
        );
    }

    public static function unsignedTinyInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('unsignedTinyInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:255', 'min:0'],
            [],
            []
        );
    }

    public static function unsignedSmallInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('unsignedSmallInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:65535', 'min:0'],
            [],
            []
        );
    }

    public static function unsignedMediumInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('unsignedMediumInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:16777215', 'min:0'],
            [],
            []
        );
    }

    public static function unsignedInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('unsignedInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer', 'max:4294967295', 'min:0'],
            [],
            []
        );
    }

    public static function unsignedBigInteger(bool $auto_increment = false)
    {
        return new static(
            [new MigrationMethod('unsignedBigInteger', [$auto_increment])],
            'integer',
            'int',
            ['integer'],
            [],
            []
        );
    }

    public static function boolean()
    {
        return new static(
            [new MigrationMethod('boolean')],
            'boolean',
            'bool',
            ['boolean'],
            [],
            []
        );
    }

    /**
     * 256 bytes
     *
     * @return static
     */
    public static function tinyText()
    {
        return new static(
            [new MigrationMethod('tinyText')],
            'string',
            'string',
            ['max:256'],
            [],
            []
        );
    }

    /**
     * ~64kb
     *
     * @return static
     */
    public static function text()
    {
        return new static(
            [new MigrationMethod('text')],
            'string',
            'string',
            ['max:65535'],
            [],
            []
        );
    }

    /**
     * ~16MB
     *
     * @return static
     */
    public static function mediumText()
    {
        return new static(
            [new MigrationMethod('mediumText')],
            'string',
            'string',
            ['max:16777215'],
            [],
            []
        );
    }

    /**
     * ~4GB
     * 
     * @return static
     */
    public static function longText()
    {
        return new static(
            [new MigrationMethod('longText')],
            'string',
            'string',
            ['max:4294967295'],
            [],
            []
        );
    }

    public static function date()
    {
        return new static(
            [new MigrationMethod('date')],
            'date',
            'Carbon',
            ['date'],
            [],
            []
        );
    }

    public static function timestamp($precision = 0)
    {
        return new static(
            [new MigrationMethod('timestamp', [$precision])],
            'datetime',
            'Carbon',
            ['date'],
            [],
            []
        );
    }

    public static function enum($options = [])
    {
        return new static(
            [new MigrationMethod('enum', [$options])],
            'string',
            'string',
            ['in:' . implode(',', $options)],
            [],
            []
        );
    }

    public static function carbon($precision = 0)
    {
        return self::timestamp($precision);
    }

    public function nullable()
    {
        $this->is_nullable = true;
        $this->appendValidators(['nullable']);
        array_push($this->migration_methods, new MigrationMethod('nullable'));
        return $this;
    }

    public function unique()
    {
        $this->is_unique = true;
        array_push($this->migration_methods, new MigrationMethod('unique'));
        return $this;
    }

    public function default($default)
    {
        $this->default = $default;
        array_push($this->migration_methods, new MigrationMethod('default', [$default]));
        return $this;
    }

    public function setName(string $column_name): SchemaItemInterface
    {
        // Dont double set the column name, this could lead to weird issues when hydrating
        if (isset($this->column_name)) {
            return $this;
        }

        $this->column_name = $column_name;
        if (isset($this->migration_methods[0])) {
            $this->migration_methods[0]->setColumnName($column_name);
        }

        return $this;
    }
    
    public function getName(): string
    {
        return $this->column_name;
    }

    public function pushMigrationMethod(MigrationMethod $migrationMethod)
    {
        array_push($this->migration_methods, $migrationMethod);

        return $this;
    }

    public function appendValidators(array $validators)
    {
        $this->validators = array_merge($this->validators, $validators);

        return $this;
    }

    public function appendCreateValidators(array $create_validators)
    {
        $this->create_validators = array_merge($this->create_validators, $create_validators);

        return $this;
    }

    public function appendUpdateValidators(array $update_validators)
    {
        $this->update_validators = array_merge($this->update_validators, $update_validators);

        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return [$this];
    }

    public function getCastTo(): string
    {
        return $this->cast_to;
    }

    public function getDataType(): string
    {
        return $this->data_type;
    }

    public function getIsNullable(): bool
    {
        return $this->is_nullable;
    }
}