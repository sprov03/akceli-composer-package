<?php echo '<?php' . PHP_EOL;
/**
 * @var $Middleware
 */
?>

namespace Tests\Http\Middleware;

use Tests\TestCase;

/**
 * Class <?=$Middleware?>MiddlewareTest
 *
 * Documentation: https://laravel-news.com/testing-laravel-middleware
 *
 * @package Tests\Http\Middleware
 */
class <?=$Middleware?>MiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function middlewareWorks()
    {
        /**
         * Allows for middle ware to throw exceptions and be caught in the test
         * Keep this if you are expecting Errors to be thrown
         */
        $this->withExceptionHandling();

        $response = $this->postJson('/some-url-that-will-trigger-the-middle-ware', []);
    }
}
