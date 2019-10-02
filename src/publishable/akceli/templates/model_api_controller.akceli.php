[[open_php_tag]]

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
            '[[model_names]]' => <?=$table->ModelName?>::paginate(50)
        ]);

        return View::make('models.[[model_names]].index', compact('state'));
    }

    /**
     * Get <?=$table->ModelName?> Create Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $state = json_encode([
            '[[model_name]]' => <?=$table->ModelName?>::baseTemplate()
        ]);

        return View::make('models.[[model_names]].create', compact('state'));
    }

    /**
     * Get <?=$table->ModelName?> Edit Page
     *
     * @param $[[model_name]]_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($[[model_name]]_id)
    {
        $state = json_encode([
            '[[model_name]]' => <?=$table->ModelName?>::findOrFail($[[model_name]]_id)
        ]);

        return View::make('models.[[model_names]].edit', compact('state'));
    }

    /**
     * Get all [[ModelNames]]
     *
     * GET /[[model_names]]
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
     * Get Single [[model_name_studly_case_singular]]
     *
     * GET /[[model_names]]/{<?=$table->ModelName?>_id}
     *
     * @param $[[model_name]]_id
     *
     * @return <?=$table->ModelName?>
     */
    public function show($[[model_name]]_id)
    {
        return <?=$table->ModelName?>::findOrFail($[[model_name]]_id);
    }

    /**
     * Create Single [[model_name_studly_case_singular]]
     *
     * POST /[[model_names]]
     *
     * @param Request $request
     *
     * @return <?=$table->ModelName?>
     */
    public function store(Request $request)
    {
        $this->validate($request, <?=$table->ModelName?>::rules());

        return <?=$table->ModelName?>::create($request->all());
    }

    /**
     * Update Site [[model_name_studly_case_singular]]
     *
     * PUT /[[model_names]]/{<?=$table->ModelName?>_id}
     *
     * @param $[[model_name]]_id <?=$table->ModelName?> id
     * @param Request $request
     *
     * @return <?=$table->ModelName?>
     */
    public function update($[[model_name]]_id, Request $request)
    {
        $this->validate($request, <?=$table->ModelName?>::rules());

        $<?=$table->ModelName?> = <?=$table->ModelName?>::findOrFail($[[model_name]]_id);
        $<?=$table->ModelName?>->update($request->all());

        return $<?=$table->ModelName?>;
    }

    /**
     * Destroy <?=$table->ModelName?>
     *
     * DELETE /[[model_names]]/{<?=$table->ModelName?>_id}
     *
     * @param $[[model_name]]_id
     *
     * @return <?=$table->ModelName?>
     */
    public function destroy($[[model_name]]_id)
    {
        $<?=$table->ModelName?> = <?=$table->ModelName?>::findOrFail($[[model_name]]_id);
        $<?=$table->ModelName?>->delete();

        return $<?=$table->ModelName?>;
    }
}
