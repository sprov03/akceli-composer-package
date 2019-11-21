<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace Factories;

use App\Models\<?=$table->ModelName?>;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Faker\Generator as Faker;

/**
 *  Documentation:  TODO add link to documentation about how to best use these factories setup
 */
class <?=$table->ModelName?>Factory
{
    /**
     * @param array $data
     *
     * @return <?=$table->ModelName . PHP_EOL?>
     */
    public static function makeDefault(array $data = [])
    {
        $authUser = Auth::user();
        /** @var Faker $faker */
        $faker = app(Faker::class);

        $<?=$table->modelName?> = new <?=$table->ModelName?>();
<?php foreach ($table->columns as $column): ?>
        $<?=$table->modelName?>-><?=$column->getField()?> = $faker-><?=$column->getField()?>;
<?php endforeach; ?>
        $<?=$table->modelName?>->forceFill($data);

<?php if ($table->hasField('account_id')): ?>
        if (!$<?=$table->modelName?>->account) {
            /** @var Account $account */
            $account = ($authUser) ? $authUser->account : AccountFactory::createDefault();
            $<?=$table->modelName?>->account()->associate($account);
        }

<?php endif; ?>
<?php if ($table->hasField('user_id')): ?>
        if (!$<?=$table->modelName?>->user) {
            /** @var User $user */
            $user = $authUser ?: UserFactory::createDefault();
            $<?=$table->modelName?>->user()->associate($user);
        }

<?php endif; ?>
        return $<?=$table->modelName?>;
    }

    /**
     * @param array $data
     *
     * @return <?=$table->ModelName . PHP_EOL?>
     */
    public static function createDefault(array $data = [])
    {
        $<?=$table->modelName?> = self::makeDefault($data);
        $<?=$table->modelName?>->save();
        return $<?=$table->modelName?>->fresh();
    }

    /**
     * @param int $number
     * @param array $data
     *
     * @return <?=$table->ModelName?>[]|Collection
     */
    public static function createDefaults(int $number, array $data = [])
    {
        /** @var <?=$table->ModelName?>[]|Collection <?=$table->modelNames?> */
        $<?=$table->modelNames?> = new Collection();
        for($i = 0; $i < $number; $i++) {
            $<?=$table->modelNames?>->push(self::createDefault($data));
        }

        return $<?=$table->modelNames?>;
    }
}
