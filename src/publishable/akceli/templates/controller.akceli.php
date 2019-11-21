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
        $<?=$table->modelName?> = new <?=$table->ModelName?>($request->validated());
        $<?=$table->modelName?>->save();

        return redirect("/<?=$table->modelNamesKabob?>/{$<?=$table->modelName?>->id}/edit");
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
     * @param  <?=$table->ModelName?> $<?=$table->modelName . PHP_EOL?>
     * @return \Illuminate\Contracts\View\View
     */
    public function update(Update<?=$table->ModelName?>Request $request, <?=$table->ModelName?> $<?=$table->modelName?>)
    {
        $<?=$table->modelName?>->update($request->validated());

        return redirect('/<?=$table->modelNamesKabob?>');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param <?=$table->ModelName?> $<?=$table->modelName . PHP_EOL?>
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function destroy(<?=$table->ModelName?> $<?=$table->modelName?>)
    {
        $<?=$table->modelName?>->delete();

        return redirect('/<?=$table->modelNamesKabob?>');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param <?=$table->ModelName?> $<?=$table->modelName . PHP_EOL?>
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(<?=$table->ModelName?> $<?=$table->modelName?>)
    {
        return View::make('models.<?=$table->modelNames?>.edit', ['<?=$table->modelName?>' => $<?=$table->modelName?>]);
    }
}
