[[open_php_tag]]

namespace Tests\App\Http\Controllers;

use Tests\TestCase;
use [[namespace]]\[[ModelName]];

class [[ModelName]]ControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index()
    {
        $this->get("/[[model_names]]");

        $this->assertResponseOk();

        $this->seeJsonStructure([
            'total',
            'per_page',
            'current_page',
            'data' => [
                [
<?php foreach ($columns as $column): ?>
                    '<?=$column->Field?>',
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
        $[[model_name]] = [[ModelName]]::first();

        $this->get("/[[model_names]]/{$[[model_name]]->id}");

        $this->assertResponseOk();
        $this->seeJsonStructure([
<?php foreach ($columns as $column): ?>
            '<?=$column->Field?>',
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function store()
    {
        $request = [
<?php foreach ($columns as $column): ?>
            // '<?=$column->Field?>' => 99999,
<?php endforeach; ?>
        ];

        $this->post("/[[model_names]]", $request);
        $this->assertResponseOk();

        $this->seeJsonStructure([
<?php foreach ($columns as $column): ?>
            '<?=$column->Field?>',
<?php endforeach; ?>
        ]);
        $this->seeInDatabase('[[table_name]]', [
<?php foreach ($columns as $column): ?>
            // '<?=$column->Field?>' => 99999,
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function update()
    {
        $[[model_name]] = [[ModelName]]::first();

        $request = [
<?php foreach ($columns as $column): ?>
            // '<?=$column->Field?>' => 99999,
<?php endforeach; ?>
        ];

        $this->put("/[[model_names]]/{$[[model_name]]->id}", $request);
        $this->assertResponseOk();

        $this->seeJsonStructure([
<?php foreach ($columns as $column): ?>
            '<?=$column->Field?>',
<?php endforeach; ?>
        ]);
        $this->seeInDatabase('[[table_name]]', [
<?php foreach ($columns as $column): ?>
            // '<?=$column->Field?>' => 99999,
<?php endforeach; ?>
        ]);
    }

    /**
     * @test
     */
    public function destroy()
    {
        $[[model_name]] = [[ModelName]]::first();

        $this->delete("/[[model_names]]/{$[[model_name]]->id}");
        $this->assertResponseOk();

        $this->assertInstanceSoftDeleted($[[model_name]]);
        $this->seeJsonStructure([
<?php foreach ($columns as $column): ?>
            '<?=$column->Field?>',
<?php endforeach; ?>
        ]);
    }

}
