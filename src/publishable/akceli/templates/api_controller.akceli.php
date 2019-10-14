<?php echo '<?php';
/** @var  TemplateData $table */
use Akceli\TemplateData;?>

namespace App\Http\Controllers\Api;

use App\Http\Requests\Create[[ModelName]]Request;
use App\Http\Requests\Patch[[ModelName]]Request;
use App\Http\Controllers\Controller;
use App\Resources\[[ModelName]]Resource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\[[ModelName]];
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class [[ModelName]]Controller extends Controller
{
    public static function apiRoutes()
    {
        Route::post('[[model-names]]', 'Api\[[ModelName]]Controller@create');
        Route::put('[[model-names]]/{[[modelName]]}', 'Api\[[ModelName]]Controller@patch');
        Route::delete('[[model-names]]/{[[modelName]]}', 'Api\[[ModelName]]Controller@delete');
        Route::get('[[model-names]]', 'Api\[[ModelName]]Controller@getCollection');
        Route::get('[[model-names]]/{[[modelName]]}', 'Api\[[ModelName]]Controller@get');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getCollection()
    {
        return [[ModelName]]Resource::collection([[ModelName]]::all());
    }

    /**
     * @param [[ModelName]] $[[modelName]]
     *
     * @return [[ModelName]]Resource
     */
    public function get([[ModelName]] $[[modelName]])
    {
        return new [[ModelName]]Resource($[[modelName]]);
    }

    /**
     * @param Create[[ModelName]]Request $request
     *
     * @return Response
     */
    public function create(Create[[ModelName]]Request $request)
    {
        $[[modelName]]Resource = [[ModelName]]Resource::create(
<?php foreach ($table->columns as $column): ?>
<?php if($table->columns->last() === $column): ?>
            $request->validated()['<?=$column->getField()?>']<?="\n"?>
<?php else: ?>
            $request->validated()['<?=$column->getField()?>']<?=",\n"?>
<?php endif; ?>
<?php endforeach; ?>
        );

        return new [[ModelName]]Resource($[[modelName]]);
    }

    /**
     * @param Patch[[ModelName]]Request $request
     * @param [[ModelName]] $[[modelName]]
     * @return Response
     */
    public function patch(Patch[[ModelName]]Request $request, [[ModelName]] $[[modelName]])
    {
        (new [[ModelName]]Resource($[[modelName]]))->patch($request->validated());

        return new [[ModelName]]Resource($[[modelName]]);
    }

    /**
     * @param [[ModelName]] $[[modelName]]
     *
     * @return Response
     * @throws \Exception
     */
    public function delete([[ModelName]] $[[modelName]])
    {
        $[[modelName]]Resource = new [[ModelName]]Resource($[[modelName]]);
        $[[modelName]]Resource->delete();

        return Response::create('[[ModelName]] was archived', 204);
    }
}
