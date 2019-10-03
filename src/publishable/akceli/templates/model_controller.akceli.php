<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use <?=$table->namespace?>\<?=$table->ModelName?>;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class <?=$table->ModelName?>Controller extends Controller
{
    /**
     * Get all <?=$table->ModelNames . PHP_EOL?>
     *
     * GET /<?=$table->model_names . PHP_EOL?>
     *
     * @param Request $request
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        return View::make('models.<?=$table->model_names?>.index', ['<?=$table->model_names?>' => <?=$table->ModelName?>::all()]);
    }

    /**
     * Get <?=$table->ModelName?> Create Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('models.<?=$table->model_names?>.create');
    }

    /**
     * Get <?=$table->ModelName?> Edit Page
     *
     * @param $<?=$table->model_name?>_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($<?=$table->model_name?>_id)
    {
        return View::make('models.<?=$table->model_names?>.edit', ['<?=$table->model_name?>' => <?=$table->ModelName?>::findOrFail($<?=$table->model_name?>_id)]);
    }

    /**
     * Create Single <?=$table->ModelName . PHP_EOL?>
     *
     * POST /<?=$table->model_names . PHP_EOL?>
     *
     * @param Request $request
     *
     * @return <?=$table->ModelName . PHP_EOL?>
     */
    public function store(Request $request)
    {
        $this->validate($request, <?=$table->ModelName?>::rules());
        $<?=$table->ModelName?> = <?=$table->ModelName?>::create($request->all());

        return View::make('models.<?=$table->model_names?>.edit', ['<?=$table->ModelName?>' => $<?=$table->ModelName?>]);
    }

    /**
     * Update Site <?=$table->ModelName . PHP_EOL?>
     *
     * PUT /<?=$table->model_names?>/{<?=$table->ModelName?>_id}/update
     *
     * @param $<?=$table->model_name?>_id <?=$table->ModelName?> id
     * @param Request $request
     *
     * @return <?=$table->ModelName . PHP_EOL?>
     */
    public function update($<?=$table->model_name?>_id, Request $request)
    {
        $this->validate($request, <?=$table->ModelName?>::rules());

        $<?=$table->ModelName?> = <?=$table->ModelName?>::findOrFail($<?=$table->model_name?>_id);
        $<?=$table->ModelName?>->update($request->all());

        return View::make('models.<?=$table->model_names?>.edit', ['<?=$table->model_name?>' => $<?=$table->ModelName?>]);
    }

    /**
     * Delete <?=$table->ModelName . PHP_EOL?>
     * Not best practice but simple delete with link instead of a form
     *
     * Get /<?=$table->model_names?>/{<?=$table->ModelName?>_id}/delete
     *
     * @param $<?=$table->model_name?>_id
     *
     * @return View
     */
    public function destroy($<?=$table->model_name?>_id)
    {
        $<?=$table->ModelName?> = <?=$table->ModelName?>::findOrFail($<?=$table->model_name?>_id);
        $<?=$table->ModelName?>->delete();

        return View::make('models.<?=$table->model_names?>.index', ['<?=$table->model_names?>' => <?=$table->ModelName?>::all()]);
    }
}
