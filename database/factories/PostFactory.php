<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$user = User::first();
$factory->define(Post::class, function (Faker $faker) use($user) {
    return [
        'user_id' => $user->id,
        'title' => $faker->sentence,
        'description' => $faker->text,
    ];
});
