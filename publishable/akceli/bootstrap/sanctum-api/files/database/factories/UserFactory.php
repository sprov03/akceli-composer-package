<?php

namespace Factories;

use App\Models\User;
use Illuminate\Support\Collection;
use Faker\Generator as Faker;

/**
 *  Documentation:  TODO add link to documentation about how to best use these factories setup
 */
class UserFactory
{
    /**
     * @param array $data
     *
     * @return User
     */
    public static function makeDefault(array $data = [])
    {
        /** @var Faker $faker */
        $faker = app(Faker::class);

        $user = new User();
        $user->name = $faker->name;
        $user->email = $faker->email;
//        $user->email_verified_at = $faker->email_verified_at;
        $user->password = $faker->password;
//        $user->remember_token = $faker->remember_token;
//        $user->created_at = $faker->created_at;
//        $user->updated_at = $faker->updated_at;
        $user->forceFill($data);

        return $user;
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public static function createDefault(array $data = [])
    {
        $user = self::makeDefault($data);
        $user->save();
        return $user->fresh();
    }

    /**
     * @param int $number
     * @param array $data
     *
     * @return User[]|Collection
     */
    public static function createDefaults(int $number, array $data = [])
    {
        /** @var User[]|Collection users */
        $users = new Collection();
        for($i = 0; $i < $number; $i++) {
            $users->push(self::createDefault($data));
        }

        return $users;
    }
}
