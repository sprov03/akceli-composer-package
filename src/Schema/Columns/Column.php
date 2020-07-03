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
            'integer',
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

    public static function timestamp($precision = 0)
    {
        return new static(
            'timestamp',
            [$precision],
            'datetime',
            'datetime',
            ['date'],
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