<?php echo '<?php';
/**
 * @var string $Model
 */
use Illuminate\Support\Str;
?>


namespace App\Policies;

use App\Models\[[Model]];
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Documentation: https://laravel.com/docs/6.x/authorization
 */
class [[Model]]Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any dogs.
     *
     * @param  User $authUser
     * @return mixed
     */
    public function viewAny(User $authUser)
    {
        //
    }

    /**
     * Determine whether the user can view the dog.
     *
     * @param  User $authUser
     * @param  [[Model]] $<?=Str::snake($Model) . PHP_EOL?>
     * @return mixed
     */
    public function view(User $authUser, [[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }

    /**
     * Determine whether the user can create dogs.
     *
     * @param  User $authUser
     * @return mixed
     */
    public function create(User $authUser)
    {
        //
    }

    /**
     * Determine whether the user can update the dog.
     *
     * @param  User $authUser
     * @param  [[Model]] $<?=Str::snake($Model) . PHP_EOL?>
     * @return mixed
     */
    public function update(User $authUser, [[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }

    /**
     * Determine whether the user can delete the dog.
     *
     * @param  User $authUser
     * @param  [[Model]] $<?=Str::snake($Model) . PHP_EOL?>
     * @return mixed
     */
    public function delete(User $authUser, [[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }

    /**
     * Determine whether the user can restore the dog.
     *
     * @param  User $authUser
     * @param  [[Model]] $<?=Str::snake($Model) . PHP_EOL?>
     * @return mixed
     */
    public function restore(User $authUser, [[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the dog.
     *
     * @param  User $authUser
     * @param  [[Model]] $<?=Str::snake($Model) . PHP_EOL?>
     * @return mixed
     */
    public function forceDelete(User $authUser, [[Model]] $<?=Str::snake($Model)?>)
    {
        //
    }
}
