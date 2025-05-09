<?php

namespace Tests;

use App\Entities\User;
use Database\Seeders\DatabaseSeeder;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Logging\DebugStack;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Sowl\JsonApi\Testing\DoctrineRefreshDatabase;
use Sowl\JsonApi\Testing\InteractWithDoctrineDatabase;

abstract class TestCase extends BaseTestCase
{
    use DoctrineRefreshDatabase;
    use InteractWithDoctrineDatabase;
    use WithFaker;

    protected DebugStack $dbDebugStack;

    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDoctrineDatabase();
        $this->interactsWithDoctrineDatabase();

        // TODO: Move this to the base library
        // You can dd($this->dbDebugStack->queries) to see the queries
        $this->em()
            ->getConnection()
            ->getConfiguration()
            ->setSQLLogger($this->dbDebugStack = new DebugStack());
    }

    protected function doctrineRunMigrations(): bool
    {
        return false;
    }

    protected function seedDatabase(): void
    {
        $this->seed(DatabaseSeeder::class);
    }

    protected function em(): EntityManager
    {
        return $this->app->make(EntityManager::class);
    }

    protected function actingAsUser(): User
    {
        $user = entity(User::class)->create();
        $this->actingAs($user);

        return $user;
    }

    protected function actingAsAdmin(): User
    {
        $user = entity(User::class, 'admin')->create();
        $this->actingAs($user);

        return $user;
    }

    /**
     * Assert that the response contains JSON:API validation errors for the given pointers.
     *
     * @param  array  $pointers  Array of JSON pointer strings, e.g., ['/data/attributes/email']
     */
    public function assertJsonApiValidationErrors($response, array $pointers): void
    {
        $json = $response->json();
        $errors = $json['errors'] ?? [];
        $found = [];
        foreach ($pointers as $pointer) {
            foreach ($errors as $error) {
                if (isset($error['source']['pointer']) && $error['source']['pointer'] === $pointer) {
                    $found[] = $pointer;
                    break;
                }
            }
        }
        foreach ($pointers as $pointer) {
            $this->assertContains($pointer, $found, "Validation error for pointer '{$pointer}' not found in response.");
        }
    }
}
