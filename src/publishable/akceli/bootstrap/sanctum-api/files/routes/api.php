<?php

use Akceli\RealtimeClientStoreSync\ClientStore\ClientStoreController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
/** Auto Import */

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

Route::middleware(['auth:sanctum'])->group(function () {
    // This registers the Client Store Endpoint
    Route::prefix('client-store')->group(function () {
        ClientStoreController::apiRoutes();
    });
    Route::get('validate-cookies', function () {});

    /**
     * All routes that will use the Client Store Middleware
     */
    Route::middleware('client-store')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        /** Import Api routes */
    });
});


/**
 * Auth Routes
 */
Route::post('request-access-token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::post('register', 'Auth\\RegisterController@register');
Route::post('login', 'Auth\\LoginController@login');
Route::post('logout', 'Auth\\LoginController@logout');

