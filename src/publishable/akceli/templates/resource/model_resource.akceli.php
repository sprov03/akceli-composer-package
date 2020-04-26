<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>


namespace App\Resources;

use App\Models\[[ModelName]];
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class [[ModelName]]Resource
 *
 * @property [[ModelName]] resource
 *
 * @package App\Http\Resources
 *
 * @example https://laravel.com/docs/6.x/eloquent-resources#concept-overview
 */
class [[ModelName]]Resource extends JsonResource
{
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
<?php foreach ($table->columns as $column): ?>
<?php if ($table->columns->last() === $column): ?>
            '<?=$column->Field?>' => $this->resource-><?=$column->Field . PHP_EOL?>
<?php else:?>
            '<?=$column->Field?>' => $this->resource-><?=$column->Field?>,
<?php endif;?>
<?php endforeach; ?>
        ];
    }
}
