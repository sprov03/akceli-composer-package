<?php

namespace Akceli\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class PrototypeApiController extends Controller
{
    public static function routes()
    {
        Route::post('{table}', function (Request $request, string $table) {
            $modelName = 'App\\' . config('akceli.model_directory') . '\\' . Str::studly(Str::singular($table));
            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = new $modelName();

            $validatedModel = $modelName::getValidatedCreateData($request);
            $model->forceFill($validatedModel->toArray());
            $model->save();

            return $model;
        });

        Route::put('{table}/{id}', function (Request $request, string $table, int $id) {
            $modelName = 'App\\' . config('akceli.model_directory') . '\\' . Str::studly(Str::singular($table));
            /** @var \App\Models\BaseModelTrait $model */
            $model = $modelName::findOrFail($id);

            $validatedModel = $modelName::getValidatedUpdateData($request);
            $model->forceFill($validatedModel->toArray());
            $model->save();

            return $model;
        });

        Route::get('{table}', function (Request $request, string $table) {
            $modelName = 'App\\' . config('akceli.model_directory') . '\\' . Str::studly(Str::singular($table));
            /** @var \Illuminate\Database\Eloquent\Model $modelName */
            return $modelName::query()->paginate();
        });

        Route::get('{table}/{id}', function (Request $request, string $table, int $id) {
            $modelName = 'App\\' . config('akceli.model_directory') . '\\' . Str::studly(Str::singular($table));
            /** @var \App\Models\BaseModelTrait $model */
            $model = $modelName::findOrFail($id);
            return $model;
        });

        Route::delete('{table}/{id}', function (Request $request, string $table, int $id) {
            $modelName = 'App\\' . config('akceli.model_directory') . '\\' . Str::studly(Str::singular($table));
            /** @var \App\Models\BaseModelTrait $model */
            $model = $modelName::findOrFail($id);
            return $model;
        });
    }

}