<?php echo '<?php' . PHP_EOL;
/**
 * @var $Exception
 */
?>

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class [[Exception]]
 * @package App\Exceptions
 *
 * Documentation: https://laravel.com/docs/6.x/errors#renderable-exceptions
 */
class [[Exception]] extends Exception
{
    /**
     * Report the exception.
     *
     * Documentation: https://laravel.com/docs/6.x/errors#renderable-exceptions
     *
     * @return void
     */
     public function report()
     {
        //
    }

    /**
     * Render the exception as an HTTP response.
     *
     * Documentation: https://laravel.com/docs/6.x/errors#render-method
     *
     * @param  Request  $request
     * @return Response
     */
    public function render($request)
    {
        //
    }
}
