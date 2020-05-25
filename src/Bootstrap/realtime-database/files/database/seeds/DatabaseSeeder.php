<?php

use Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = UserFactory::makeDefault();
        $user->name = 'Test User';
        $user->email = 'testUser@example.com';
        $user->password = 'password';
        $user->save();

        // $this->call(UserSeeder::class);
    }
}
