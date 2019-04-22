<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ApiKey::class, function (Faker $faker) {
    return [
        'name' => '',
        'key' => '',
    ];
});
