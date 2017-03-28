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
        'country'               => $faker->country,
        'phone'                 => $faker->phoneNumber,
        'mobile'                => $faker->phoneNumber,
        'role'                  => \App\Models\User::BASIC_ROLE,
        'isActive'              => rand(0,1),
        'profileImage'          => $faker->imageUrl('100')
    ];
});