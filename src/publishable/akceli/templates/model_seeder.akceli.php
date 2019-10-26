<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

use Illuminate\Database\Seeder;
use App\Models\<?=$table->ModelName?>;

class <?=$table->ModelName?>Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $<?=$table->ModelNames?> = factory(<?=$table->ModelName?>::class, 20)->create([
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
