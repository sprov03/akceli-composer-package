<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>


namespace <?=$table->namespace?>;

use <?=$table->fully_qualified_base_model_name?>;
<?php if ($table->hasField('deleted_at')): ?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php endif; ?>
use Carbon\Carbon;

/**
 * Class <?=$table->ModelName . PHP_EOL?>
 *
 * Database Fields
<?php foreach ($table->columns as $column): ?>
 * @property <?=$column->getDataType()?> $<?=$column->getField() . PHP_EOL?>
<?php endforeach; ?>
 *
 * Relationships
 *
 * @package <?=$table->namespace . PHP_EOL?>
 */
class <?=$table->ModelName?> extends <?=$table->base_model . PHP_EOL?>
{
<?php if ($table->hasField('deleted_at')): ?>
    use SoftDeletes;

<?php endif; ?>
    protected $table = '<?=$table->table_name?>';

<?php if ($table->missingField('updated_at') && ! $table->hasField('created_at')): ?>
    public $timestamps = false;
<?php endif; ?>
<?php if ($table->primaryKey !== 'id'): ?>
    public $incrementing = false;
    protected $primaryKey = '<?=$table->primaryKey?>';

<?php endif; ?>
    protected $casts = [
<?php foreach ($table->columns as $column): ?>
<?php if ($column->hasCastsToAttribute()): ?>
        '<?=$column->getField()?>' => '<?=$column->getCastsToAttribute()?>',
<?php endif; ?>
<?php endforeach; ?>
    ];

    protected $fillable = [
<?php foreach ($table->columns as $column): ?>
        //'<?=$column->getField()?>',
<?php endforeach; ?>
    ];
}
