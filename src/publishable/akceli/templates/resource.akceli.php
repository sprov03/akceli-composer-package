<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;
use Illuminate\Support\Str;
?>


namespace App\Resources;

use App\Models\[[ModelName]];
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class [[Resource]]
 *
 * @property [[ModelName]] $resource
 *
 * @package App\Http\Resources
 *
 * @example https://laravel.com/docs/6.x/eloquent-resources#concept-overview
 */
class [[Resource]] extends JsonResource
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
            '<?=$column->getField()?>' => $this->resource-><?=$column->getField()?>,
<?php endforeach; ?>
        ];
    }
}
