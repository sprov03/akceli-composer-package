<?php echo '<?php' . PHP_EOL;
/**
 * @var $Mailable
 * @var $markdown_path
 * @var $mailable_type
 */
?>

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Documentation
 */
class <?=$Mailable?>Mailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this-><?=$mailable_type?>('emails.<?=$markdown_path?>');
    }
}

