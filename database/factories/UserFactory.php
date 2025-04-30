<?php

use App\Entities\Role;
use App\Entities\User;
use Doctrine\Common\Collections\ArrayCollection;
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
        'permissions' => $attributes['permissions'] ?? [],
    ];
});

$factory->defineAs(User::class, 'admin', function (Faker $faker, array $attributes = []) {
    return [
        'name' => $attributes['name'] ?? $faker->name,
        'email' => $attributes['email'] ?? $faker->unique()->safeEmail,
        'password' => $attributes['password'] ?? password_hash('password', PASSWORD_BCRYPT),
        'roles' => new ArrayCollection([
            Role::admin(),
        ]),
    ];
});
