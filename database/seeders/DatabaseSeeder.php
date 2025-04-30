<?php

namespace Database\Seeders;

use App\Entities\Role;
use Doctrine\ORM\EntityManager;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(EntityManager $em): void
    {
        // Create admin role
        if (!$em->getRepository(Role::class)->findOneBy(['name' => Role::ADMIN])) {
            entity(Role::class, Role::ADMIN)->create();
        }
    }
}
