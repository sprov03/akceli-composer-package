<?php

namespace Akceli\Schema\Columns;

use Akceli\Schema\SchemaInterface;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class Column
 * @package Akceli\Schema\Columns
 * @mixin Blueprint
 */
class Column
{
    public bool $is_nullable = false;
    public bool $is_unique = false;
    public $default;

    public string $column_type;
    public string $column_name;
    public array $column_args = [];
    public string $cast_to = 'string';
    public string $data_type = 'string';
    public array $validators = [];
    public array $create_validators = [];
    public array $update_validators = [];
    
    public function __construct(
        string $column_type,
        array $column_args = [],
        string $cast_to = 'string',
        string $data_type = 'string',
        array $validators = [],
        array $create_validators = [],
        array $update_validators = []
    ) {
        $this->column_type = $column_type;
        $this->column_args = $column_args;
        $this->cast_to = $cast_to;
        $this->data_type = $data_type;
        $this->validators = $validators;
        $this->create_validators = $create_validators;
        $this->update_validators = $update_validators;
    }

    public static function new(
       string $column_type,
       array $column_args = [],
       string $cast_to = 'string',
       string $data_type = 'string',
       array $validators = [],
       array $create_validators = [],
       array $update_validators = []
    ) {
        return new static(
            $column_type,
            $column_args,
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
            'id',
            [],
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
            'uuid',
            [],
            'string',
            'string',
            [],
            [],
            []
        );
    }

    public static function string($length = 255)
    {
        return new static(
            'string',
            [$length],
            'string',
            'string',
            ['max:' . $length],
            [],
            []
        );
    }

    public static function tinyInteger(bool $auto_increment = false)
    {
        return new static(
            'tinyInteger',
            [$auto_increment],
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
            'smallInteger',
            [$auto_increment],
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
            'mediumInteger',
            [$auto_increment],
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
            'integer',
            [$auto_increment],
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
            'bigInteger',
            [$auto_increment],
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
            'unsignedTinyInteger',
            [$auto_increment],
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
            'unsignedSmallInteger',
            [$auto_increment],
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
            'unsignedMediumInteger',
            [$auto_increment],
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
            'unsignedInteger',
            [$auto_increment],
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
            'unsignedBigInteger',
            [$auto_increment],
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
            'boolean',
            [],
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
            'tinyText',
            [],
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
            'text',
            [],
            'string',
            'string',
            ['max:65535'],
            [],
            []
        );
    }

    /**
     * ~16GB
     *
     * @return static
     */
    public static function mediumText()
    {
        return new static(
            'mediumText',
            [],
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
            'longText',
            [],
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
            'date',
            [],
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
            'timestamp',
            [$precision],
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
            'enum',
            [$options],
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
        return $this;
    }

    public function unique()
    {
        $this->is_unique = true;
        return $this;
    }

    public function default($default)
    {
        $this->default = $default;
        return $this;
    }

    public function setColumnName(string $column_name)
    {
        $this->column_name = $column_name;
        return $this;
    }

    public function appendValidators(array $validators)
    {
        array_push($this->validators, $validators);
        return $this;
    }

    public function appendCreateValidators(array $create_validators)
    {
        array_push($this->create_validators, $create_validators);
        return $this;
    }

    public function appendUpdateValidators(array $update_validators)
    {
        array_push($this->update_validators, $update_validators);
        return $this;
    }
}