<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Models\<?=$table->namespace?>\<?=$table->ModelName?>;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class <?=$table->ModelName?>Controller extends Controller
{
    /**
     * Get <?=$table->ModelName?> Index Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function indexPage()
    {
        $state = json_encode([
            '<?=$table->model_names?>' => <?=$table->ModelName?>::paginate(50)
        ]);

        return View::make('models.<?=$table->model_names?>.index', compact('state'));
    }

    /**
     * Get <?=$table->ModelName?> Create Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $state = json_encode([
            '<?=$table->model_name?>' => <?=$table->ModelName?>::baseTemplate()
        ]);

        return View::make('models.<?=$table->model_names?>.create', compact('state'));
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
        $state = json_encode([
            '<?=$table->model_name?>' => <?=$table->ModelName?>::findOrFail($<?=$table->model_name?>_id)
        ]);

        return View::make('models.<?=$table->model_names?>.edit', compact('state'));
    }

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
        return <?=$table->ModelName?>::paginate($request->input('page_size', 50));
    }

    /**
     * Get Single <?=$table->ModelName . PHP_EOL?>
     *
     * GET /<?=$table->model_names?>/{<?=$table->ModelName?>_id}
     *
     * @param $<?=$table->model_name?>_id
     *
     * @return <?=$table->ModelName . PHP_EOL?>
     */
    public function show($<?=$table->model_name?>_id)
    {
        return <?=$table->ModelName?>::findOrFail($<?=$table->model_name?>_id);
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

        return <?=$table->ModelName?>::create($request->all());
    }

    /**
     * Update Site <?=$table->ModelName . PHP_EOL?>
     *
     * PUT /<?=$table->model_names?>/{<?=$table->ModelName?>_id}
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

        return $<?=$table->ModelName?>;
    }

    /**
     * Destroy <?=$table->ModelName . PHP_EOL?>
     *
     * DELETE /<?=$table->model_names?>/{<?=$table->ModelName?>_id}
     *
     * @param $<?=$table->model_name?>_id
     *
     * @return <?=$table->ModelName . PHP_EOL?>
     */
    public function destroy($<?=$table->model_name?>_id)
    {
        $<?=$table->ModelName?> = <?=$table->ModelName?>::findOrFail($<?=$table->model_name?>_id);
        $<?=$table->ModelName?>->delete();

        return $<?=$table->ModelName?>;
    }
}
