[[open_php_tag]]

use Illuminate\Database\Seeder;
use [[namespace]]\[[ModelName]];

class [[ModelName]]Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $[[modelNames]] = factory([[ModelName]]::class, 20)->create([
<?php foreach ($columns as $column): ?>
<?php if (isset($column->faker_type)): ?>
        '<?=$column->Field?>' => $faker-><?=$column->faker_type?>,
<?php else: ?>
        // '<?=$column->Field?>' => $faker-><?=$column->Field?>,
<?php endif; ?>
<?php endforeach; ?>
        ]);
    }
}
