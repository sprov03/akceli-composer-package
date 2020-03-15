<?php echo '<?php';
/**
 * @var string $Model
 */
use Illuminate\Support\Str;
?>


namespace App\Observers;

use App\Models\[[Model]];

/**
 * Documentation: https://laravel.com/docs/6.x/eloquent#observers
 */
class [[Observer]]
{
    /**
     * Handle the [[Model]] "created" event.
     *
     * @param  [[Model]]  $<?=Str::snake($Model) . PHP_EOL?>
     * @return void
     */
    public function created([[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }

    /**
     * Handle the [[Model]] "updated" event.
     *
     * @param  [[Model]]  $<?=Str::snake($Model) . PHP_EOL?>
     * @return void
     */
    public function updated([[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }

    /**
     * Handle the [[Model]] "deleted" event.
     *
     * @param  [[Model]]  $<?=Str::snake($Model) . PHP_EOL?>
     * @return void
     */
    public function deleted([[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }

    /**
     * Handle the [[Model]] "forceDeleted" event.
     *
     * @param  [[Model]]  $<?=Str::snake($Model) . PHP_EOL?>
     * @return void
     */
    public function forceDeleted([[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }
}
