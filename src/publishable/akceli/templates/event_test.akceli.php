<?php echo '<?php' . PHP_EOL;
/**
 * @var $Event
 */
?>

namespace Tests\Events;

use App\Events\ExampleEvent;
use Tests\TestCase;

class ExampleEventTest extends TestCase
{
    /**
     * @test
     */
    public function whenExampleEventFired()
    {
        event(new ExampleEvent());
    }
}
