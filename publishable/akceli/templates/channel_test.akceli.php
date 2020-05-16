<?php echo '<?php' . PHP_EOL;
/**
 * @var $Channel
 */
use Illuminate\Support\Str;
?>

namespace Tests\Broadcasting;

use App\Broadcasting\[[Channel]];
use Factories\UserFactory;
use Tests\TestCase;

class [[Channel]]Test extends TestCase
{
    /**
     * @test
     */
    public function canJoinChannel()
    {
        $user = UserFactory::createDefault();
        /** @var [[Channel]] $<?=Str::snake($Channel)?> */
        $<?=Str::snake($Channel)?>Channel = app([[Channel]]::class);

        $this->assertTrue($<?=Str::snake($Channel)?>Channel->join($user));
    }
}
