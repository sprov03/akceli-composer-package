<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Documentation: https://laravel.com/docs/6.x/validation#available-validation-rules
 */
class [[Request]] extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
<?php if ($table->columns): ?>
<?php foreach ($table->columns as $column): ?>
<?php if ($column->hasValidationRules()): ?>
            '<?=$column->getField()?>' => '<?=$column->getValidationRulesAsString()?>',
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
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
