<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>


namespace Tests\App\Http\Controllers;

use App\Models\<?=$table->ModelName?>;
use Factories\<?=$table->ModelName?>Factory;
use Tests\TestCase;

class [[Controller]]Test extends TestCase
{
    /**
     * @test
     */
    public function index()
    {
        $response = $this->get("/<?=$table->model_names?>");
        $response->assertOk();
    }

    /**
     * @test
     */
    public function store()
    {
        $data_request = [
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>' => '999999',
<?php endforeach; ?>
        ];

        $response = $this->post("/<?=$table->model_names?>", $data_request);
        $response->assertStatus(302);
        /** @var <?=$table->ModelName?> $<?=$table->modelName?> */
        $<?=$table->modelName?> = <?=$table->ModelName?>::all()->last();

        $this->assertDatabaseHas('<?=$table->model_names?>', [
            'id' => $<?=$table->modelName?>->id,
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>' => '999999',
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function create()
    {
        $response = $this->get("/<?=$table->model_names?>/create");
        $response->assertOk();
    }


    /**
     * @test
     */
    public function show()
    {
        $<?=$table->modelName?> = <?=$table->ModelName?>Factory::createDefault();

        $response = $this->get("/<?=$table->model_names?>/{$<?=$table->modelName?>->id}");
        $response->assertOk();
    }

    /**
     * @test
     */
    public function update()
    {
        $<?=$table->modelName?> = <?=$table->ModelName?>Factory::createDefault();

        $request_data = [
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>' => '999999',
<?php endforeach; ?>
        ];

        $response = $this->put("/<?=$table->model_names?>/{$<?=$table->modelName?>->id}", $request_data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('<?=$table->model_names?>', [
            'id' => $<?=$table->modelName?>->id,
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>' => '999999',
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function destroy()
    {
        $<?=$table->modelName?> = <?=$table->ModelName?>Factory::createDefault();

        $response = $this->delete("/<?=$table->model_names?>/{$<?=$table->modelName?>->id}");
        $response->assertStatus(302);

<?php if ($table->hasField('deleted_at')): ?>
        $this->assertSoftDeleted($<?=$table->modelName?>);
<?php else: ?>
        $this->assertDatabaseMissing('<?=$table->model_names?>', [
            'id' => $<?=$table->modelName?>->id
        ]);
<?php endif; ?>
    }
}

