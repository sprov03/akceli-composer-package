<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\Controller;
use App\Resources\UserResource;
use App\Models\Services\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public static function apiRoutes()
    {
        Route::post('users', 'Api\UserController@create');
        Route::put('users/{user}', 'Api\UserController@update');
        Route::delete('users/{user}', 'Api\UserController@delete');
        Route::get('users', 'Api\UserController@getCollection');
        Route::get('users/{user}', 'Api\UserController@get');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getCollection()
    {
        return UserResource::collection(User::all());
    }

    /**
     * @param User $user
     *
     * @return UserResource
     */
    public function get(User $user)
    {
        return new UserResource($user);
    }

    /**
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function create(CreateUserRequest $request)
    {
        $userService = UserService::create(
            $request->validated()['id'],
            $request->validated()['name'],
            $request->validated()['email'],
            $request->validated()['email_verified_at'],
            $request->validated()['password'],
            $request->validated()['remember_token'],
            $request->validated()['created_at'],
            $request->validated()['updated_at']
        );

        return Response::create('User was created', 201);
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->Service()->update(
            $request->validated()['id'],
            $request->validated()['name'],
            $request->validated()['email'],
            $request->validated()['email_verified_at'],
            $request->validated()['password'],
            $request->validated()['remember_token'],
            $request->validated()['created_at'],
            $request->validated()['updated_at']
        );

        return Response::create('User was updated', 200);
    }

    /**
     * @param User $user
     *
     * @return Response
     * @throws \Exception
     */
    public function delete(User $user)
    {
        $user->Service()->delete();

        return Response::create('User was archived', 204);
    }
}
