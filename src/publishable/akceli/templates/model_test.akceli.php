<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;
use Illuminate\Support\Str; ?>

namespace Tests\App\Models;

use Tests\TestCase;
use Factories\<?=$table->ModelName?>Factory;
use App\Models\<?=$table->ModelName?>;

class <?=$table->ModelName?>Test extends TestCase
{
    /**
     * @test
     */
    public function relationships()
    {
        $<?=$table->modelName?> = <?=$table->ModelName?>Factory::createDefault();

        $this->assertInstanceOf(<?=$table->ModelName?>::class, $<?=$table->modelName?>);
<?php foreach ($table->columns as $column): ?>
<?php if (Str::contains($column->getField(), '_id')): ?>
<?php $relationship = str_replace('_id', '', $column->getField()); ?>

        $this->assertInstanceOf(<?=Str::studly(Str::singular($relationship))?>::class, $<?=$table->modelName?>-><?=Str::camel(Str::singular($relationship))?>);
        $this->assertCollectionOf(<?=Str::studly(Str::singular($relationship))?>::class, $<?=$table->modelName?>-><?=Str::camel(Str::plural($relationship))?>);
<?php endif; ?>
<?php endforeach; ?>
    }
}
