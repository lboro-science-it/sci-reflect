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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Activity::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->sentence,
        'status' => 'open'
    ];
});

$factory->define(App\Block::class, function(Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph,
        'activity_id' => function() {
            return factory(App\Activity::class)->create()->id;
        }
    ];
});

$factory->define(App\Category::class, function(Faker\Generator $faker) {
    return [
        'activity_id' => function() {
            return factory(App\Activity::class)->create()->id;
        },
        'name' => $faker->sentence,
        'color' => '#ffffff',
        'icon_href' => 'https://www.google.co.uk'
    ];
});

$factory->define(App\Round::class, function(Faker\Generator $faker) {
    $activityId = factory(App\Activity::class)->create()->id;
    return [
        'activity_id' => $activityId,
        'round_number' => 1,
        'title' => $faker->sentence,
        'block_id' => function() {
            return factory(App\Block::class)->create([
                'activity_id' => $activityId,
            ])->id;
        }
    ];
});

