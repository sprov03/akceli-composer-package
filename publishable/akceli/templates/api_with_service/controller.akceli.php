<?php use Akceli\TemplateData;

echo '<?php' . PHP_EOL;
/**
 * @var TemplateData $table
 */
?>

namespace App\Http\Controllers\Api;

use App\Http\Requests\Create[[ModelName]]Request;
use App\Http\Requests\Update[[ModelName]]Request;
use App\Http\Controllers\Controller;
use App\Resources\[[ModelName]]Resource;
use App\Models\Services\[[ModelName]]Service;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\[[ModelName]];
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class [[ModelName]]Controller extends Controller
{
    public static function apiRoutes()
    {
        Route::post('[[model_names]]', 'Api\[[ModelName]]Controller@create');
        Route::put('[[model_names]]/{[[modelName]]}', 'Api\[[ModelName]]Controller@update');
        Route::delete('[[model_names]]/{[[modelName]]}', 'Api\[[ModelName]]Controller@delete');
        Route::get('[[model_names]]', 'Api\[[ModelName]]Controller@getCollection');
        Route::get('[[model_names]]/{[[modelName]]}', 'Api\[[ModelName]]Controller@get');
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
        $[[modelName]]Service = [[ModelName]]Service::create(
<?php foreach ($table->columns as $index => $column): ?>
<?php if(count($table->columns) === $index + 1): ?>
            $request->validated()['<?=$column->Field?>']<?="\n"?>
<?php else: ?>
            $request->validated()['<?=$column->Field?>']<?=",\n"?>
<?php endif; ?>
<?php endforeach; ?>
        );

        return Response::create('[[ModelName]] was created', 201);
    }

    /**
     * @param Update[[ModelName]]Request $request
     * @param [[ModelName]] $[[modelName]]
     * @return Response
     */
    public function update(Update[[ModelName]]Request $request, [[ModelName]] $[[modelName]])
    {
        $[[modelName]]->Service()->update(
<?php foreach ($table->columns as $index => $column): ?>
<?php if(count($table->columns) === $index + 1): ?>
            $request->validated()['<?=$column->Field?>']<?="\n"?>
<?php else: ?>
            $request->validated()['<?=$column->Field?>']<?=",\n"?>
<?php endif; ?>
<?php endforeach; ?>
        );

        return Response::create('[[ModelName]] was updated', 200);
    }

    /**
     * @param [[ModelName]] $[[modelName]]
     *
     * @return Response
     * @throws \Exception
     */
    public function delete([[ModelName]] $[[modelName]])
    {
        $[[modelName]]->Service()->delete();

        return Response::create('[[ModelName]] was archived', 204);
    }
}
