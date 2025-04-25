<?php

namespace Database\Seeders;

use App\Entities\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin role
        entity(Role::class, Role::ADMIN)->create();
    }
}
