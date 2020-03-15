<?php echo '<?php' . PHP_EOL;
/**
 * @var string $Command
 * @var string $Signature
 */
?>

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Documentation: https://laravel.com/docs/6.x/artisan#writing-commands
 */
class [[Command]] extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '[[Signature]]';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}

