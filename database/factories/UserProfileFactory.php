<?php

use Faker\Generator as Faker;


$factory->define(App\UserProfile::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $faker->tollFreePhoneNumber
    ];
});
