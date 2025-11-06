<?php echo '<?php' . PHP_EOL;
/** @var  TemplateData $table */
use Akceli\TemplateData;
?>

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
<?php if ($table->hasField('deleted_at')): ?>
use Illuminate\Database\Eloquent\SoftDeletes;
<?php endif; ?>
use Carbon\Carbon;

/**
 * Class <?=$table->ModelName . PHP_EOL?>
 *
 * Database Fields
<?php foreach ($table->columns as $column): ?>
 * @property <?=$column->getColumnSetting('php_class_doc_type', 'string')?> $<?=$column->getField() . PHP_EOL?>
<?php endforeach; ?>
 *
 * Relationships
 *
 * @package App\Models
 */
class <?=$table->ModelName?> extends Model
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
<?php if ($column->getColumnSetting('casts')): ?>
        '<?=$column->getField()?>' => '<?=$column->getColumnSetting('casts')?>',
<?php endif; ?>
<?php endforeach; ?>
    ];

    protected $fillable = [
<?php foreach ($table->columns as $column): ?>
        //'<?=$column->getField()?>',
<?php endforeach; ?>
    ];
}
