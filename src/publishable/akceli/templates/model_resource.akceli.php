<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace App\Resources;

use App\Models\[[ModelName]];
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Broadcasting\Channel;

/**
 * Class [[ModelName]]Resource
 *
 * @property [[ModelName]] resource
 *
 * @package App\Http\Resources
 *
 * @example https://laravel.com/docs/5.8/eloquent-resources#concept-overview
 */
class [[ModelName]]Resource extends JsonResource
{
    const Model = [[ModelName]]::class;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'class_name' => $this->class_name,
<?php foreach ($table->columns as $column): ?>
<?php if ($table->columns->last() === $column): ?>
            '<?=$column->Field?>' => $this->resource-><?=$column->Field?>
<?php else:?>
            '<?=$column->Field?>' => $this->resource-><?=$column->Field?>,
<?php endif;?>
<?php endforeach; ?>
        ];
    }

    public static function create(
<?php foreach ($table->columns as $column): ?>
<?php if ($column->getColumnSetting('php_class_doc_type', null)): ?>
<?php if($table->columns->last() === $column): ?>
        <?=$column->getColumnSetting('php_class_doc_type')?> $<?=$column->getField() . "\n"?>
<?php else: ?>
        <?=$column->getColumnSetting('php_class_doc_type')?> $<?=$column->getField() . ",\n"?>
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

        $[[modelName]]Resource = new [[ModelName]]Resource($[[modelName]]);
        return $[[modelName]]Resource;
    }

    /**
     * @param array $validated
     * @return $this
     */
    public function patch(array $validated)
    {
        $this->resource->update($validated);
    }


    /**
     * @throws \Exception
     */
    public function delete() {
        $this->resource->delete();
    }
}
