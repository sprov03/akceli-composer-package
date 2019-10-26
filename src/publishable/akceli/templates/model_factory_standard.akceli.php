<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

/**
 *  Documentation: https://laravel.com/docs/5.8/database-testing#writing-factories
 *
 *  @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(App\Models\<?=$table->ModelName?>::class, function (Faker\Generator $faker) use ($factory) {

    return [
<?php foreach ($table->columns as $column): ?>
<?php if (isset($column->faker_type)): ?>
        '<?=$column->getField()?>' => $faker-><?=$column->faker_type?>,
<?php else: ?>
        // '<?=$column->getField()?>' => $faker-><?=$column->getField()?>,
<?php endif; ?>
<?php endforeach; ?>
    ];
});
