<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;
use Illuminate\Support\Str; ?>


namespace Factories;

use App\Models\<?=$table->ModelName?>;
use Illuminate\Support\Collection;
use Faker\Generator as Faker;

/**
 *  Documentation:  TODO add link to documentation about how to best use these factories setup
 */
class [[Factory]]
{
    /**
     * @param array $data
     *
     * @return <?=$table->ModelName . PHP_EOL?>
     */
    public static function makeDefault(array $data = [])
    {
        /** @var Faker $faker */
        $faker = app(Faker::class);

        $<?=$table->modelName?> = new <?=$table->ModelName?>();
<?php foreach ($table->columns as $column): ?>
<?php if (!$column->endsWith('_id')): ?>
        $<?=$table->modelName?>-><?=$column->getField()?> = $faker-><?=$column->getField()?>;
<?php endif; ?>
<?php endforeach; ?>
        $<?=$table->modelName?>->forceFill($data);

<?php foreach ($table->columns as $column): ?>
<?php if ($column->endsWith('_id')): ?>
        if (!$<?=$table->modelName?>-><?=Str::camel(str_replace('_id', '', $column->getField()))?>) {
            $<?=$table->modelName?>-><?=Str::camel(str_replace('_id', '', $column->getField()))?>()->associate(<?=Str::studly(str_replace('_id', '', $column->getField()))?>Factory::createDefault());
        }

<?php endif; ?>
<?php endforeach; ?>

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
