<?php echo '<?php' . PHP_EOL;
/**
 * @var $Channel
 */
use Illuminate\Support\Str;
?>

namespace Tests\Broadcasting;

use App\Broadcasting\<?=$Channel?>Channel;
use Factories\UserFactory;
use Tests\TestCase;

class <?=$Channel?>ChannelTest extends TestCase
{
    /**
     * @test
     */
    public function canJoinChannel()
    {
        $user = UserFactory::createDefault();
        /** @var <?=$Channel?>Channel $<?=Str::snake($Channel)?>Channel */
        $<?=Str::snake($Channel)?>Channel = app(<?=$Channel?>Channel::class);

        $this->assertTrue($<?=Str::snake($Channel)?>Channel->join($user));
    }
}
