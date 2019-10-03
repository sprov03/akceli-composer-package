<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace Tests\<?=$table->namespace?>;

use Tests\TestCase;
use Factories\<?=$table->ModelName?>Factory;
use <?=$table->namespace?>\<?=$table->ModelName?>;

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
<?php if (str_contains($column->getField(), '_id')): ?>
<?php $relationship = str_replace('_id', '', $column->getField()); ?>

        $this->assertInstanceOf(<?=studly_case(str_singular($relationship))?>::class, $<?=$table->ModelName?>-><?=camel_case(str_singular($relationship))?>);
        $this->assertCollectionOf(<?=studly_case(str_singular($relationship))?>::class, $<?=$table->ModelName?>-><?=camel_case(str_plural($relationship))?>);
<?php endif; ?>
<?php endforeach; ?>
    }
}
