<?php echo '<?php' . PHP_EOL;
/**
 * @var $Event
 */
?>

namespace Tests\Events;

use App\Events\[[Event]]Event;
use Tests\TestCase;

class [[Event]]Test extends TestCase
{
    /**
     * @test
     */
    public function when[[Event]]EventFired()
    {
        event(new [[Event]]Event());
    }
}
