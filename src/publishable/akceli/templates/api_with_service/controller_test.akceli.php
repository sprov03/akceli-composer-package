<?php echo '<?php';
/** @var  TemplateData $table */
use Illuminate\Support\Str;
use Akceli\TemplateData;?>

namespace Tests\App\Http\Controllers;

use Tests\TestCase;
use Factories\UserFactory;
use App\Models\[[ModelName]];
use Factories\[[ModelName]]Factory;

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

        $response = $this->getJson("/api/[[model-names]]");
        $response->assertOk();

        /** @var [[ModelName]][] $[[modelNames]] */
        $[[modelNames]] = json_decode($response->getContent());

        $this->assertCount(2, $[[modelNames]]);
        $response->assertJsonStructure([
            [
<?php foreach ($table->columns as $column): ?>
<?php if ($table->columns->last() === $column): ?>
                '<?=$column->getField()?>'
<?php else: ?>
                '<?=$column->getField()?>',
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

        $response = $this->getJson("/api/[[model-names]]/{$[[modelName]]->id}");

        $response->assertOk();
        $response->assertJsonStructure([
<?php foreach ($table->columns as $column): ?>
<?php if ($table->columns->last() === $column): ?>
            '<?=$column->getField()?>'
<?php else: ?>
            '<?=$column->getField()?>',
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

        $response = $this->postJson("/api/[[model-names]]", $request);

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

        $response = $this->putJson("/api/[[model-names]]/{$[[modelName]]->id}", $request);
        $response->assertOk();

        $this->assertDatabaseHas('[[model_names]]', [
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>' => 99999,
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

        $response = $this->deleteJson("/api/[[model-names]]/{$[[modelName]]->id}");
        $response->assertStatus(204);

        $this->assertSoftDeleted($[[modelName]]);
    }
}
