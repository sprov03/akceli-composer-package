<?php echo '<?php';
/**
 * @var $Listener
 */
?>


namespace Tests\Listeners;

use Tests\TestCase;
use App\Events\[[Event]];

class [[Listener]]Test extends TestCase
{
    /**
     * @test
     */
    public function exampleListener()
    {
        event(new [[Event]]());
    }
}

