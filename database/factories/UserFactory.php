<?php // database/factories/UserFactory.php

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'uid'                   => str_random(32),
        'firstName'             => $faker->firstName,
        'lastName'              => $faker->lastName,
        'email'                 => $faker->email,
        'middleName'            => $faker->lastName,
        'password'              => \Illuminate\Support\Facades\Hash::make('test-password'),
        'address'               => $faker->address,
        'zipCode'               => $faker->postcode,
        'username'              => $faker->userName,
        'city'                  => $faker->city,
        'state'                 => $faker->state,
        'country'               => 'USA',
        'phone'                 => $faker->phoneNumber,
        'mobile'                => $faker->phoneNumber,
        'type'                  => 'USER',
        'isActive'              => rand(0,1)
    ];
});