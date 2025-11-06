<?php echo '<?php';
/**
 * @var $Event
 */
use Illuminate\Support\Str;
?>


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Documentation: https://laravel.com/docs/6.x/events#introduction
 */
class [[Event]]
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('<?=Str::singular(Str::snake($Event))?>_channel');
    }
}

