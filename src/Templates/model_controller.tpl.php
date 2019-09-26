[[open_php_tag]]

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use [[namespace]]\[[ModelName]];
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class [[ModelName]]Controller extends Controller
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
        return View::make('models.[[model_names]].index', ['[[model_names]]' => [[ModelName]]::all()]);
    }

    /**
     * Get [[ModelName]] Create Page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('models.[[model_names]].create');
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
        return View::make('models.[[model_names]].edit', ['[[model_name]]' => [[ModelName]]::findOrFail($[[model_name]]_id)]);
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
        $[[modelName]] = [[ModelName]]::create($request->all());

        return View::make('models.[[model_names]].edit', ['[[modelName]]' => $[[modelName]]]);
    }

    /**
     * Update Site [[model_name_studly_case_singular]]
     *
     * PUT /[[model_names]]/{[[ModelName]]_id}/update
     *
     * @param $[[model_name]]_id [[ModelName]] id
     * @param Request $request
     *
     * @return [[ModelName]]
     */
    public function update($[[model_name]]_id, Request $request)
    {
        $this->validate($request, [[ModelName]]::rules());

        $[[modelName]] = [[ModelName]]::findOrFail($[[model_name]]_id);
        $[[modelName]]->update($request->all());

        return View::make('models.[[model_names]].edit', ['[[model_name]]' => $[[modelName]]]);
    }


    /**
     * Delete [[ModelName]]
     * Not best practice but simple delete with link instead of a form
     *
     * Get /[[model_names]]/{[[ModelName]]_id}/delete
     *
     * @param $[[model_name]]_id
     *
     * @return View
     */
    public function destroy($[[model_name]]_id)
    {
        $[[ModelName]] = [[ModelName]]::findOrFail($[[model_name]]_id);
        $[[ModelName]]->delete();

        return View::make('models.[[model_names]].index', ['[[model_names]]' => [[ModelName]]::all()]);
    }
}
