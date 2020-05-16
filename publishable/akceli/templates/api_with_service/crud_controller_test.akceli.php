<?php
use Akceli\TemplateData;
use Illuminate\Support\Str;

echo '<?php' . PHP_EOL;
/**
 * @var TemplateData $table
 */
?>

namespace Tests\Http\Controllers;

use Tests\TestCase;
use Factories\UserFactory;
use App\Models\[[ModelName]];
use Factories\[[ModelName]]Factory;
use Illuminate\Support\Facades\Event;

class [[ModelName]]ControllerTest extends TestCase
{
    /**
     * @test
     */
    public function canGetCollection()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        [[ModelName]]Factory::createDefaults(2);

        $response = $this->getJson("/v1/[[model_names]]");
        $response->assertOk();

        /** @var [[ModelName]][] $[[modelName]] */
        $[[modelName]] = json_decode($response->getContent());

        $this->assertCount(2, $[[modelName]]);
        $response->assertJsonStructure([
            [
<?php foreach ($table->columns as $column): ?>
<?php if (in_array($column->getField(), ['created_by', 'updated_by', 'deleted_by', 'deleted_at', 'deleted_by_type', 'account_id'])): ?>
                // '<?=Str::camel($column->Field)?>',
<?php elseif ($column->Field !== 'deleted_at' || $column->Field !== 'deleted_by' || $column->Field !== 'account_id'): ?>
                '<?=$column->Field?>',
<?php endif; ?>
<?php endforeach; ?>
            ]
        ]);
    }

    /**
     * @test
     */
    public function canGet()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $[[modelName]] = [[ModelName]]Factory::createDefault();

        $response = $this->getJson("/v1/[[model_names]]/{$[[modelName]]->id}");

        $response->assertOk();
        $response->assertJsonStructure([
<?php foreach ($table->columns as $column): ?>
<?php if (in_array($column->getField(), ['created_by', 'updated_by', 'deleted_by', 'deleted_at', 'deleted_by_type', 'account_id'])): ?>
                // '<?=Str::camel($column->Field)?>',
<?php elseif ($column->Field !== 'deleted_at' || $column->Field !== 'deleted_by' || $column->Field !== 'account_id'): ?>
                '<?=$column->Field?>',
<?php endif; ?>
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function canCreate()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $request = [
<?php foreach ($table->columns as $column): ?>
            //'<?=$column->getField()?>' => 99999,
<?php endforeach; ?>
        ];

        $response = $this->postJson("/v1/[[model_names]]", $request);

        $response->assertStatus(201);
        $[[modelName]] = [[ModelName]]::last();

        $this->assertDatabaseHas('[[model_names]]', [
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>' => 99999,
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function canUpdate()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $[[modelName]] = [[ModelName]]Factory::createDefault();

        $request = [
<?php foreach ($table->columns as $column): ?>
            //'<?=$column->getField()?>' => 99999,
<?php endforeach; ?>
        ];

        $response = $this->putJson("/v1/[[model_names]]/{$[[modelName]]->id}", $request);
        $response->assertOk();

        $this->assertDatabaseHas('[[model_names]]', [
<?php foreach ($table->columns as $column): ?>
            '<?=$column->Field?>' => 99999,
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function canDestroy()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $[[modelName]] = [[ModelName]]Factory::createDefault();

        $response = $this->deleteJson("/v1/[[model_names]]/{$[[modelName]]->id}");
        $response->assertStatus(204);

        $this->assertSoftDeleted($[[modelName]]);
    }
}
