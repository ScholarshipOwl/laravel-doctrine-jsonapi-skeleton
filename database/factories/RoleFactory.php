<?php

declare(strict_types=1);

use App\Entities\Role;
use Faker\Generator as Faker;
use LaravelDoctrine\ORM\Testing\Factory;

/** @var Factory $factory */
$factory->define(Role::class, fn (Faker $faker, array $attributes = []) => [
    'name' => $attributes['name'] ?? $faker->unique()->word,
    'permissions' => $attributes['permissions'] ?? [],
]);

$factory->state(Role::class, Role::ADMIN, fn (Faker $faker, array $attributes = []) => [
    'name' => Role::ADMIN,
    'permissions' => $attributes['permissions'] ?? [],
]);

$factory->defineAs(Role::class, 'admin', fn (Faker $faker, array $attributes = []) => [
    'name' => Role::ADMIN,
    'permissions' => $attributes['permissions'] ?? [],
]);
