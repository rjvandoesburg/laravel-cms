<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Cms\Modules\Core\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Cms\Modules\Core\Models\UserMeta::class, function (Faker\Generator $faker) {

    return [
        'user_id' => factory(Cms\Modules\Core\Models\User::class)->create()->id,
        'key' => $faker->word,
        'value' => $faker->sentence,
    ];
});
