<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>


namespace App\Models\Services;

use App\Models\[[ModelName]];

class [[ModelName]]Service extends ModelService
{
    private [[ModelName]] $[[modelName]];

    /**
     * [[ModelName]]Service constructor.
     * @param [[ModelName]] $[[modelName]]
     */
    public function __construct([[ModelName]] $[[modelName]])
    {
        $this->[[modelName]] = $[[modelName]];
        $this->model = $[[modelName]];
    }

    public static function create(
<?php foreach ($table->columns as $column): ?>
<?php if ($column->getColumnSetting('php_method_doc_type', null)): ?>
<?php if($table->columns->last() === $column): ?>
        <?=$column->getColumnSetting('php_method_doc_type')?> $<?=$column->getField()?><?=($column->isNullable()) ? " = null\n" : "\n"?>
<?php else: ?>
        <?=$column->getColumnSetting('php_method_doc_type')?> $<?=$column->getField()?><?=($column->isNullable()) ? " = null,\n" : ",\n"?>
<?php endif; ?>
<?php else: ?>
<?php if($table->columns->last() === $column): ?>
        $<?=$column->Field . "\n"?>
<?php else: ?>
        $<?=$column->Field . ",\n"?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
    ) {
        $[[modelName]] = new [[ModelName]]();

<?php foreach ($table->columns as $column): ?>
        $[[modelName]]-><?=$column->Field?> = $<?=$column->Field?>;
<?php endforeach; ?>

        $[[modelName]]->save();

        return $[[modelName]];
    }

    public function update(
<?php foreach ($table->columns as $column): ?>
<?php if ($column->getColumnSetting('php_method_doc_type', null)): ?>
<?php if($table->columns->last() === $column): ?>
        <?=$column->getColumnSetting('php_method_doc_type')?> $<?=$column->getField()?><?=($column->isNullable()) ? " = null\n" : "\n"?>
<?php else: ?>
        <?=$column->getColumnSetting('php_method_doc_type')?> $<?=$column->getField()?><?=($column->isNullable()) ? " = null,\n" : ",\n"?>
<?php endif; ?>
<?php else: ?>
<?php if($table->columns->last() === $column): ?>
        $<?=$column->Field . "\n"?>
<?php else: ?>
        $<?=$column->Field . ",\n"?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
    ) {
<?php foreach ($table->columns as $column): ?>
        $this->[[modelName]]-><?=$column->getField()?> = $<?=$column->getField()?>;
<?php endforeach; ?>
        $this->[[modelName]]->save();
    }
}
