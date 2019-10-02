[[open_php_tag]]

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use <?=$table->namespace?>\<?=$table->ModelName?>;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class <?=$table->ModelName?>Controller extends Controller
{
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
        return View::make('models.[[model_names]].index', ['[[model_names]]' => <?=$table->ModelName?>::all()]);
    }

    /**
     * Get <?=$table->ModelName?> Create Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('models.[[model_names]].create');
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
        return View::make('models.[[model_names]].edit', ['[[model_name]]' => <?=$table->ModelName?>::findOrFail($[[model_name]]_id)]);
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
        $<?=$table->ModelName?> = <?=$table->ModelName?>::create($request->all());

        return View::make('models.[[model_names]].edit', ['<?=$table->ModelName?>' => $<?=$table->ModelName?>]);
    }

    /**
     * Update Site [[model_name_studly_case_singular]]
     *
     * PUT /[[model_names]]/{<?=$table->ModelName?>_id}/update
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

        return View::make('models.[[model_names]].edit', ['[[model_name]]' => $<?=$table->ModelName?>]);
    }


    /**
     * Delete <?=$table->ModelName?>
     * Not best practice but simple delete with link instead of a form
     *
     * Get /[[model_names]]/{<?=$table->ModelName?>_id}/delete
     *
     * @param $[[model_name]]_id
     *
     * @return View
     */
    public function destroy($[[model_name]]_id)
    {
        $<?=$table->ModelName?> = <?=$table->ModelName?>::findOrFail($[[model_name]]_id);
        $<?=$table->ModelName?>->delete();

        return View::make('models.[[model_names]].index', ['[[model_names]]' => <?=$table->ModelName?>::all()]);
    }
}
