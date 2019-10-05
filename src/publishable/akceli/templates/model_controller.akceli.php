<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace App\Http\Controllers;

use App\Models\<?=$table->ModelName?>;
use App\Http\Requests\Store<?=$table->ModelName?>Request;
use App\Http\Requests\Update<?=$table->ModelName?>Request;
use Illuminate\Support\Facades\View;

class <?=$table->ModelName?>Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('models.<?=$table->modelNames?>.index', ['<?=$table->modelNames?>' => <?=$table->ModelName?>::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Store<?=$table->ModelName?>Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function store(Store<?=$table->ModelName?>Request $request)
    {
        $<?=$table->modelName?> = <?=$table->ModelName?>::create($request->validated());

        return View::make('models.<?=$table->modelNames?>.edit', ['<?=$table->modelName?>' => $<?=$table->modelName?>]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('models.<?=$table->modelNames?>.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $<?=$table->modelName?>_id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($<?=$table->modelName?>_id)
    {
        return View::make('models.<?=$table->modelNames?>.show', ['<?=$table->modelName?>' => <?=$table->ModelName?>::findOrFail($<?=$table->modelName?>_id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Update<?=$table->ModelName?>Request  $request
     * @param  <?=$table->ModelName?> $<?=$table->modelName?>
     * @return \Illuminate\Contracts\View\View
     */
    public function update(Update<?=$table->ModelName?>Request $request, <?=$table->ModelName?> $<?=$table->modelName?>)
    {
        $<?=$table->modelName?> = <?=$table->ModelName?>::create($request->validated());

        return View::make('models.<?=$table->modelNames?>.edit', ['<?=$table->modelName?>' => $<?=$table->modelName?>]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $<?=$table->modelName?>_id
     * @return \Illuminate\Contracts\View\View
     */
    public function destroy($<?=$table->modelName?>_id)
    {
        <?=$table->ModelName?>::findOrFail($<?=$table->modelName?>_id)->delete();

        return View::make('models.owners.index', ['owners' => Owner::all()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $<?=$table->modelName?>_id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($<?=$table->modelName?>_id)
    {
        return View::make('models.<?=$table->modelNames?>.edit', ['<?=$table->modelName?>' => <?=$table->ModelName?>::findOrFail($<?=$table->modelName?>_id)]);
    }
}
