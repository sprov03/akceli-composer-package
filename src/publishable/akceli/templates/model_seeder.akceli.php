[[open_php_tag]]

use Illuminate\Database\Seeder;
use <?=$table->namespace?>\<?=$table->ModelName?>;

class <?=$table->ModelName?>Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $[[modelNames]] = factory(<?=$table->ModelName?>::class, 20)->create([
<?php foreach ($table->columns as $column): ?>
<?php if (isset($column->faker_type)): ?>
        '<?=$column->getField()?>' => $faker-><?=$column->faker_type?>,
<?php else: ?>
        // '<?=$column->getField()?>' => $faker-><?=$column->getField()?>,
<?php endif; ?>
<?php endforeach; ?>
        ]);
    }
}
