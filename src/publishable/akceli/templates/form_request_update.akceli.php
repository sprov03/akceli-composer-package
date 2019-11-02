<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * https://laravel.com/docs/5.8/validation#available-validation-rules
 */
class Update[[ModelName]]Request extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
<?php foreach ($table->columns as $column): ?>
<?php if ($column->hasValidationRules()): ?>
            '<?=$column->getField()?>' => '<?=$column->getValidationRulesAsString()?>',
<?php endif; ?>
<?php endforeach; ?>
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
//            'title.required' => 'A title is required',
//            'body.required'  => 'A message is required',
        ];
    }
}
