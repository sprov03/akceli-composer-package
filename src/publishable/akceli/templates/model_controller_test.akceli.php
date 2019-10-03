<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace Tests\App\Http\Controllers;

use Tests\TestCase;
use <?=$table->namespace?>\<?=$table->ModelName?>;

class <?=$table->ModelName?>ControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index()
    {
        $this->get("/<?=$table->model_names?>");

        $this->assertResponseOk();

        $this->seeJsonStructure([
            'total',
            'per_page',
            'current_page',
            'data' => [
                [
<?php foreach ($table->columns as $column): ?>
                    '<?=$column->getField()?>',
<?php endforeach; ?>
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function show()
    {
        $<?=$table->model_name?> = <?=$table->ModelName?>::first();

        $this->get("/<?=$table->model_names?>/{$<?=$table->model_name?>->id}");

        $this->assertResponseOk();
        $this->seeJsonStructure([
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>',
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function store()
    {
        $request = [
<?php foreach ($table->columns as $column): ?>
            // '<?=$column->getField()?>' => 99999,
<?php endforeach; ?>
        ];

        $this->post("/<?=$table->model_names?>", $request);
        $this->assertResponseOk();

        $this->seeJsonStructure([
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>',
<?php endforeach; ?>
        ]);
        $this->seeInDatabase('<?=$table->table_name?>', [
<?php foreach ($table->columns as $column): ?>
            // '<?=$column->getField()?>' => 99999,
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function update()
    {
        $<?=$table->model_name?> = <?=$table->ModelName?>::first();

        $request = [
<?php foreach ($table->columns as $column): ?>
            // '<?=$column->getField()?>' => 99999,
<?php endforeach; ?>
        ];

        $this->put("/<?=$table->model_names?>/{$<?=$table->model_name?>->id}", $request);
        $this->assertResponseOk();

        $this->seeJsonStructure([
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>',
<?php endforeach; ?>
        ]);
        $this->seeInDatabase('<?=$table->table_name?>', [
<?php foreach ($table->columns as $column): ?>
            // '<?=$column->getField()?>' => 99999,
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function destroy()
    {
        $<?=$table->model_name?> = <?=$table->ModelName?>::first();

        $this->delete("/<?=$table->model_names?>/{$<?=$table->model_name?>->id}");
        $this->assertResponseOk();

        $this->assertInstanceSoftDeleted($<?=$table->model_name?>);
        $this->seeJsonStructure([
<?php foreach ($table->columns as $column): ?>
            '<?=$column->getField()?>',
<?php endforeach; ?>
        ]);
    }

}
