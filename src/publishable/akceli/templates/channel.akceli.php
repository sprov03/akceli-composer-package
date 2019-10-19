<?php echo '<?php' . PHP_EOL;
/**
 * @var $Channel
 */
?>

namespace App\Broadcasting;

use App\Models\User;

/**
 * Documentation: https://laravel.com/docs/5.8/broadcasting#defining-channel-classes
 */
class <?=$Channel?>Channel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  User  $user
     * @return array|bool
     */
    public function join(User $user)
    {
        return false;
    }
}
