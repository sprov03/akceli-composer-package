<?php echo '<php?';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(<?=$table->namespace?>\<?=$table->ModelName?>::class, function (Faker\Generator $faker) use ($factory) {

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
