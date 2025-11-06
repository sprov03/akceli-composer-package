<?php echo '<?php' . PHP_EOL;
/**
 * @var $Event
 */
?>

namespace Tests\Events;

use App\Events\[[Event]];
use Tests\TestCase;

class [[Event]]Test extends TestCase
{
    /**
     * @test
     */
    public function when[[Event]]Fired()
    {
        event(new [[Event]]());
    }
}
