<?php echo '<?php' . PHP_EOL;
/**
 * @var $Middleware
 */
?>

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class <?=$Middleware?>Middleware
 *
 * Documentation: https://laravel.com/docs/5.8/middleware#defining-middleware
 *
 * @package App\Http\Middleware
 */
class <?=$Middleware?>Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
