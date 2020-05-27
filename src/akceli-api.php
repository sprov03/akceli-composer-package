<?php

use Akceli\ApiLogger;
use Akceli\Console;
use Akceli\DevOnlyMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
 * All Routes must go in this wrapper, this makes sure they are only exposed in local enviroments
 */
Route::middleware(DevOnlyMiddleware::class)->group(function () {
    Route::get('akceli-api/version', function () {
        $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);
        $version = $composerJson['require']['akceli/laravel-code-generator'];
        return [
            'version' => $version
        ];
    });

    Route::post('akceli-api/features/upgrade-self', function () {
        shell_exec(`composer update 'akceli/laravel-code-generator'`);
        return new Response('Success', 200);
    });

    Route::post('akceli-api/generate', function (\Akceli\Generator $generator, Request $request) {
        Console::setLogger(new ApiLogger());

        $generator->handle(
            $request->get('templates', []),
            $request->get('template_data', []),
            $request->get('model_path', ''),
            $request->get('schema_data', []),
            $request->get('file_modifiers', []),
            $request->get('force', false),
        );

        return new Response(json_encode(ApiLogger::getMessages()), 200);
    });
});
