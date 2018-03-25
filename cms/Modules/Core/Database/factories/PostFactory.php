<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Cms\Modules\Core\Models\Post::class, function (Faker\Generator $faker) {
    $user = factory(Cms\Modules\Core\Models\User::class)->create();

    return [
        'author_id' => $user->id,
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});