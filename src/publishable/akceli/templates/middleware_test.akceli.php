<?php echo '<?php';
/**
 * @var $Middleware
 */
?>


namespace Tests\Http\Middleware;

use Tests\TestCase;

/**
 * Class <?=$Middleware?>MiddlewareTest
 *
 * @package Tests\Http\Middleware
 */
class <?=$Middleware?>Test extends TestCase
{
    /**
     * @test
     */
    public function middlewareWorks()
    {
        /**
         * Allows for middle ware to throw exceptions and be caught in the test
         * Keep this if you are expecting Errors to be thrown
         *
         * Documentation: https://laravel-news.com/testing-laravel-middleware
         */
        $this->withExceptionHandling();

        $response = $this->postJson('/some-url-that-will-trigger-the-middle-ware', []);
    }
}
