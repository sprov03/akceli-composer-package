<?php

namespace Akceli;

use Akceli\Schema\ColumnInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class TemplateData
 * @package Akceli
 *
 * @property string $open_php_tag
 * @property string $modelName
 * @property string $modelNames
 * @property string $ModelName
 * @property string $ModelNames
 * @property string $model_name
 * @property string $model_names
 * @property string $modelNameKabob
 * @property string $modelNamesKabob
 *
 * @property string $table_name
 * @property string $primaryKey
 * @property array $extraData
 * @property Collection|ColumnInterface[] $columns
 */
class TemplateData
{
    use \AkceliTableDataTrait;

    private $extraData = [];
    private $columns;
    /**
     * @var array
     */
    private $data;

    /**
     * TemplateData constructor.
     * @param array $data
     * @param Collection|ColumnInterface[] $columns
     */
    public function __construct(array $data, Collection $columns)
    {
        $this->columns = $columns;
        foreach ($data as $key => $value) {
            $parser = new Parser();
            $parser->addData($this->toArray());

            $this->extraData[$key] = $parser->render($value);
        }
    }

    public static function buildModelAliases(string $model_name)
    {
        return [
            'ModelName' => Str::singular(Str::studly($model_name)),
            'ModelNames' => Str::plural(Str::studly($model_name)),
            'modelName' => Str::singular(Str::camel($model_name)),
            'modelNames' => Str::plural(Str::camel($model_name)),
            'model_name' => Str::singular(Str::snake($model_name)),
            'model_names' => Str::plural(Str::snake($model_name)),
            'model-name' => Str::singular(Str::kebab($model_name)),
            'model-names' => Str::plural(Str::kebab($model_name)),
            'modelNameKabob' => Str::singular(Str::kebab($model_name)),
            'modelNamesKabob' => Str::plural(Str::kebab($model_name)),
        ];
    }

    /**
     * @param $name
     * @return mixed|string
     */
    public function __get($name)
    {
        if ($name === 'columns') {
            return $this->columns;
        }

        if (isset($this->extraData[$name])) {
            return $this->extraData[$name];
        }

        return '';
    }

    /**
     * @param string $field_name
     * @return bool
     */
    public function hasField(string $field_name): bool
    {
        return $this->columns->contains(function (ColumnInterface $column) use ($field_name) {
            return $column->getField() === $field_name;
        });
    }

    /**
     * @param string $field_name
     * @return bool
     */
    public function missingField(string $field_name): bool
    {
        return !$this->hasField($field_name);
    }

    public function toArray()
    {
        $mainData = [
            'table' => $this,
        ];

        return array_merge($mainData, $this->extraData);
    }
}
