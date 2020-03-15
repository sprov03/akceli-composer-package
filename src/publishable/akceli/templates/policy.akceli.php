<?php echo '<?php';
/**
 * @var string $Model
 * @var TemplateData $table
 */
use Akceli\TemplateData;
use Illuminate\Support\Str;
?>


namespace App\Policies;

<?php if ($table->ModelName !== 'User'): ?>
use App\Models\[[ModelName]];
<?php endif; ?>
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Documentation: https://laravel.com/docs/6.x/authorization
 */
class [[ModelName]]Policy
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
     * @param  [[ModelName]] $[[modelName]]
     * @return mixed
     */
    public function view(User $authUser, [[ModelName]] $[[modelName]])
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
     * @param  [[ModelName]] $[[modelName]]
     * @return mixed
     */
    public function update(User $authUser, [[ModelName]] $[[modelName]])
    {
        //
    }

    /**
     * Determine whether the user can delete the dog.
     *
     * @param  User $authUser
     * @param  [[ModelName]] $[[modelName]]
     * @return mixed
     */
    public function delete(User $authUser, [[ModelName]] $[[modelName]])
    {
        //
    }

    /**
     * Determine whether the user can restore the dog.
     *
     * @param  User $authUser
     * @param  [[ModelName]] $[[modelName]]
     * @return mixed
     */
    public function restore(User $authUser, [[ModelName]] $[[modelName]])
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the dog.
     *
     * @param  User $authUser
     * @param  [[ModelName]] $[[modelName]]
     * @return mixed
     */
    public function forceDelete(User $authUser, [[ModelName]] $[[modelName]])
    {
        //
    }
}
