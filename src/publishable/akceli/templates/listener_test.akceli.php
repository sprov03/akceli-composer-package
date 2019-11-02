<?php echo '<?php' . PHP_EOL;
/**
 * @var $Listener
 */
?>

namespace Tests\Listeners;

use Tests\TestCase;

class <?=$Listener?>ListenerTest extends TestCase
{
    /**
     * @test
     */
    public function exampleListener()
    {
        event(new <?=$Listener?>Event());
    }
}

