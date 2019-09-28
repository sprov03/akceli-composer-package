[[open_php_tag]]

namespace Tests\[[namespace]];

use Tests\TestCase;
use [[namespace]]\[[ModelName]];

class [[ModelName]]Test extends TestCase
{
    /**
     * @test
     */
    public function relationships()
    {
        $[[modelName]] = [[ModelName]]::first();
<?php foreach ($columns as $column): ?>
<?php if (str_contains($column->Field, '_id')): ?>
<?php $relationship = str_replace('_id', '', $column->Field); ?>

        $this->assertInstanceOf(<?=studly_case(str_singular($relationship))?>::class, $[[modelName]]-><?=camel_case(str_singular($relationship))?>);
        $this->assertCollectionOf(<?=studly_case(str_singular($relationship))?>::class, $[[modelName]]-><?=camel_case(str_plural($relationship))?>);
<?php endif; ?>
<?php endforeach; ?>
    }
}
