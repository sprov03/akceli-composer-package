[[open_php_tag]]

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Models\[[namespace]]\[[ModelName]];
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class [[ModelName]]Controller extends Controller
{
    /**
     * Get [[ModelName]] Index Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function indexPage()
    {
        $state = json_encode([
            '[[model_names]]' => [[ModelName]]::paginate(50)
        ]);

        return View::make('models.[[model_names]].index', compact('state'));
    }

    /**
     * Get [[ModelName]] Create Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $state = json_encode([
            '[[model_name]]' => [[ModelName]]::baseTemplate()
        ]);

        return View::make('models.[[model_names]].create', compact('state'));
    }

    /**
     * Get [[ModelName]] Edit Page
     *
     * @param $[[model_name]]_id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($[[model_name]]_id)
    {
        $state = json_encode([
            '[[model_name]]' => [[ModelName]]::findOrFail($[[model_name]]_id)
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
        return [[ModelName]]::paginate($request->input('page_size', 50));
    }

    /**
     * Get Single [[model_name_studly_case_singular]]
     *
     * GET /[[model_names]]/{[[ModelName]]_id}
     *
     * @param $[[model_name]]_id
     *
     * @return [[ModelName]]
     */
    public function show($[[model_name]]_id)
    {
        return [[ModelName]]::findOrFail($[[model_name]]_id);
    }

    /**
     * Create Single [[model_name_studly_case_singular]]
     *
     * POST /[[model_names]]
     *
     * @param Request $request
     *
     * @return [[ModelName]]
     */
    public function store(Request $request)
    {
        $this->validate($request, [[ModelName]]::rules());

        return [[ModelName]]::create($request->all());
    }

    /**
     * Update Site [[model_name_studly_case_singular]]
     *
     * PUT /[[model_names]]/{[[ModelName]]_id}
     *
     * @param $[[model_name]]_id [[ModelName]] id
     * @param Request $request
     *
     * @return [[ModelName]]
     */
    public function update($[[model_name]]_id, Request $request)
    {
        $this->validate($request, [[ModelName]]::rules());

        $[[ModelName]] = [[ModelName]]::findOrFail($[[model_name]]_id);
        $[[ModelName]]->update($request->all());

        return $[[ModelName]];
    }

    /**
     * Destroy [[ModelName]]
     *
     * DELETE /[[model_names]]/{[[ModelName]]_id}
     *
     * @param $[[model_name]]_id
     *
     * @return [[ModelName]]
     */
    public function destroy($[[model_name]]_id)
    {
        $[[ModelName]] = [[ModelName]]::findOrFail($[[model_name]]_id);
        $[[ModelName]]->delete();

        return $[[ModelName]];
    }
}
