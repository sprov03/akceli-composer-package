[[open_php_tag]]

namespace [[namespace]];

use [[fully_qualified_base_model_name]];
<?php if ($columns->contains(function($value) { return $value->Field === 'deleted_at';})): ?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php endif; ?>

/**
 * Class <?=$ModelName . PHP_EOL?>
 *
 * Database Fields
<?php foreach ($columns as $column): ?>
<?php if (isset($column->document_type)): ?>
 * @property <?=$column->document_type?> $<?=$column->Field . "\n"?>
<?php endif; ?>
<?php endforeach; ?>
 *
 * Relationships
 *
 * @package [[namespace]]
 */
class <?=$ModelName?> extends [[base_model]]
{
<?php if ($columns->contains(function($value) { return $value->Field === 'deleted_at';})): ?>
    use SoftDeletes;
<?php endif; ?>

    protected $table = '[[table_name]]';
<?php if (! $columns->contains(function($value) { return $value->Field === 'updated_at';}) && ! $columns->contains(function($value) { return $value->Field === 'created_at';})): ?>
    public $timestamps = false;
<?php endif; ?>
<?php if ($primary_key !== 'id'): ?>
    public $incrementing = false;
    protected $primaryKey = '<?=$primary_key?>';
<?php endif; ?>

    protected $casts = [
<?php foreach ($columns as $column): ?>
<?php if (isset($column->casts)): ?>
        '<?=$column->Field?>' => '<?=$column->casts?>',
<?php endif; ?>
<?php endforeach; ?>
    ];

    public function getFillable()
    {
        return array_keys(self::rules());
    }

    /**
    * Returns Dynamic Model Rules If necessary
    *
    * @return array
    */
    public static function rules()
    {
        return [
<?php foreach ($columns as $column): ?>
<?php if (isset($column->rules)): ?>
            '<?=$column->Field?>' => '<?=$column->rules?>',
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
}
