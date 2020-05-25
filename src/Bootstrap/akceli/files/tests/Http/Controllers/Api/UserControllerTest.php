<?php

namespace Tests\Http\Controllers\Api;

use Tests\TestCase;
use Factories\UserFactory;
use App\Models\User;

class UserControllerTest extends TestCase
{
    /**
     * @test
     */
    public function canGetCollection()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        UserFactory::createDefaults(2);

        $response = $this->getJson("/api/users");
        $response->assertOk();

        /** @var User[] $users */
        $users = json_decode($response->getContent());

        $this->assertCount(2, $users);
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'email',
                'email_verified_at',
                'password',
                'remember_token',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    /**
     * @test
     */
    public function canGet()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $user = UserFactory::createDefault();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at'
        ]);
    }

    /**
     * @test
     */
    public function canCreate()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $request = [
            //'id' => 99999,
            //'name' => 99999,
            //'email' => 99999,
            //'email_verified_at' => 99999,
            //'password' => 99999,
            //'remember_token' => 99999,
            //'created_at' => 99999,
            //'updated_at' => 99999,
        ];

        $response = $this->postJson("/api/users", $request);

        $response->assertStatus(201);
        $user = User::last();

        $this->assertDatabaseHas('users', [
            'id' => 99999,
            'name' => 99999,
            'email' => 99999,
            'email_verified_at' => 99999,
            'password' => 99999,
            'remember_token' => 99999,
            'created_at' => 99999,
            'updated_at' => 99999,
        ]);
    }

    /**
     * @test
     */
    public function canUpdate()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $user = UserFactory::createDefault();

        $request = [
            //'id' => 99999,
            //'name' => 99999,
            //'email' => 99999,
            //'email_verified_at' => 99999,
            //'password' => 99999,
            //'remember_token' => 99999,
            //'created_at' => 99999,
            //'updated_at' => 99999,
        ];

        $response = $this->putJson("/api/users/{$user->id}", $request);
        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => 99999,
            'name' => 99999,
            'email' => 99999,
            'email_verified_at' => 99999,
            'password' => 99999,
            'remember_token' => 99999,
            'created_at' => 99999,
            'updated_at' => 99999,
        ]);
    }

    /**
     * @test
     */
    public function canDestroy()
    {
        $authUser = UserFactory::createDefault();
        $this->actingAs($authUser, 'api');

        $user = UserFactory::createDefault();

        $response = $this->deleteJson("/api/users/{$user->id}");
        $response->assertStatus(204);

        $this->assertSoftDeleted($user);
    }
}
