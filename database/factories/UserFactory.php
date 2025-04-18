<?php

use App\Entities\User;
use Faker\Generator as Faker;

/* @var \LaravelDoctrine\ORM\Factories\Factory $factory */

// Doctrine factory definition for User entity
$factory->define(User::class, function (Faker $faker, array $attributes = []) {
    return [
        'name' => $attributes['name'] ?? $faker->name,
        'email' => $attributes['email'] ?? $faker->unique()->safeEmail,
        'password' => $attributes['password'] ?? password_hash('password', PASSWORD_BCRYPT),
        'emailVerifiedAt' => $attributes['emailVerifiedAt'] ?? null,
        'rememberToken' => $attributes['rememberToken'] ?? null,
    ];
});
