<?php

namespace Akceli;
use Akceli\Schema\Column;
use Illuminate\Container\Container;
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
 * @property string $app_namespace
 * @property string $table_name
 * @property string $primaryKey
 * @property array $extraData
 * @property Collection|Column[] $columns
 *
 * @mixin \AkceliExtraDataMixin
 */
class TemplateData
{
    public $open_php_tag = "<?php";
    public $app_namespace;
    public $table_name;
    public $primaryKey;
    private $extraData = [];
    public $columns;

    public $ModelName;
    public $ModelNames;
    public $modelName;
    public $modelNames;
    public $model_name;
    public $model_names;
    public $modelNamesKabob;
    public $modelNameKabob;


    /**
     * TemplateData constructor.
     * @param string $table_name
     * @param string $model_name
     * @param Collection $columns
     * @param array $extra_data
     */
    public function __construct(string $table_name, string $model_name, Collection $columns, array $extra_data = [])
    {
        $this->table_name = $table_name;
        $this->app_namespace = Container::getInstance()->getNamespace();
        $this->ModelName = Str::singular(Str::studly($model_name));
        $this->ModelNames = Str::plural(Str::studly($model_name));
        $this->modelName = Str::singular(Str::camel($model_name));
        $this->modelNames = Str::plural(Str::camel($model_name));
        $this->model_name = Str::singular(Str::snake($model_name));
        $this->model_names = Str::plural(Str::snake($model_name));
        $this->modelNameKabob = str_replace('_', '-', $this->model_name);
        $this->modelNamesKabob = str_replace('_', '-', $this->model_names);

        $this->columns = $columns;
        $this->primaryKey = $this->columns->firstWhere('Key', '==', 'PRI')->Field;

        foreach ($extra_data as $key => $value) {
            $parser = new Parser();
            $parser->addData($this->toArray());

            $this->extraData[$key] = $parser->render($value);
        }
    }

    /**
     * @param $name
     * @return mixed|string
     */
    public function __get($name)
    {
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
        return $this->columns->contains(function (Column $column) use ($field_name) {
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
            'open_php_tag' => "<?php",
            'table_name' => $this->table_name,

            'ModelName' => $this->ModelName,
            'ModelNames' => $this->ModelNames,
            'modelName' => $this->modelName,
            'modelNames' => $this->modelNames,
            'model_name' => $this->model_name,
            'model_names' => $this->model_names,
            'model-name' => $this->modelNameKabob,
            'model-names' => $this->modelNamesKabob,

            'app_namespace' => $this->app_namespace,
            'columns' => $this->columns,
            'primaryKey' => $this->primaryKey,
        ];

        return array_merge($mainData, $this->extraData);
    }
}
