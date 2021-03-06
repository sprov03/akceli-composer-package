<?php echo '<?php';
use Illuminate\Support\Str;
?>


namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Documentation: https://laravel.com/docs/6.x/validation#custom-validation-rules
 */
class [[Rule]] implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
