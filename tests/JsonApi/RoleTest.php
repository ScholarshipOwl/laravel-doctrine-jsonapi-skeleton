<?php

declare(strict_types=1);

namespace Tests\JsonApi;

use App\Entities\Role;
use App\Entities\User;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function testAuthorizationRules(): void
    {
        // Create a dummy role for testing specific ID endpoints
        $role = entity(Role::class)->create(['name' => 'TestRoleForAuth']);
        $roleId = $role->getId();

        // Endpoints to test
        $endpoints = [
            'list'   => ['method' => 'getJson', 'path' => '/roles'],
            'create' => [
                'method' => 'postJson',
                'path' => '/roles',
                'data' => ['data' => ['type' => 'roles', 'attributes' => ['name' => 'NewRole', 'permissions' => []]]]
            ],
            'view'   => ['method' => 'getJson', 'path' => "/roles/{$roleId}"],
            'update' => [
                'method' => 'patchJson',
                'path' => "/roles/{$roleId}",
                'data' => [
                    'data' => [
                        'type' => 'roles',
                        'id' => (string)$roleId,
                        'attributes' => ['name' => 'UpdatedName']
                    ]
                ]
            ],
            'delete' => ['method' => 'deleteJson', 'path' => "/roles/{$roleId}"],
        ];

        // 1. Test Unauthenticated Access (401)
        foreach ($endpoints as $action => $details) {
            $this->{$details['method']}($details['path'], $details['data'] ?? [])
                 ->assertStatus(401);
        }

        // 2. Test Regular User Access (403)
        $this->actingAsUser(); // Assumes this helper creates a non-admin user
        foreach ($endpoints as $action => $details) {
            $this->{$details['method']}($details['path'], $details['data'] ?? [])
                 ->assertStatus(403);
        }

        // 3. Test Admin Access (2xx) - Basic checks for accessibility
        $this->actingAsAdmin();
        $this->getJson('/roles')->assertOk();
        $this->getJson("/roles/{$roleId}")->assertOk();
        // POST, PATCH, DELETE require more specific assertions (like checking DB state)
        // which are better suited for dedicated tests (e.g., testAdminCanCreateRole).
        // We'll just check they don't return 401/403 here.
        $this->postJson('/roles', $endpoints['create']['data'])->assertStatus(201); // Created
        $this->patchJson("/roles/{$roleId}", $endpoints['update']['data'])->assertOk();
        // Re-create the role for the delete test since the previous one might be updated
        $roleToDelete = entity(Role::class)->create(['name' => 'ToDeleteRole']);
        $this->deleteJson("/roles/{$roleToDelete->getId()}")->assertStatus(204); // No Content
    }

    /**
     * Test GET /roles/{id} returns JSON:API compliant response for existing role.
     */
    public function testCanGetRoleByIdJsonapi(): void
    {
        $user = entity(User::class)->create();
        $this->actingAs($user);
        // Create a role entity using Doctrine factory
        $role = entity(Role::class)->create();

        $this->actingAsAdmin();
        // Perform GET request to JSON:API endpoint
        $response = $this->getJson("/roles/{$role->getId()}");

        // Assert response is successful
        $response->assertStatus(200);

        // Assert JSON:API structure
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'permissions',
                    // Add other Role attributes exposed by your JSON:API resource
                ],
            ],
        ]);

        // Assert correct data is returned
        $response->assertJson([
            'data' => [
                'type' => 'roles',
                'id' => (string) $role->getId(),
                'attributes' => [
                    'name' => $role->getName(),
                    'permissions' => $role->getPermissions(),
                ],
            ],
        ]);
    }

    /**
     * Test GET /roles/{id} returns 404 for non-existent role.
     */
    public function testGetRoleByIdReturns404ForMissingRole(): void
    {
        $user = entity(User::class)->create();
        $this->actingAs($user);
        $response = $this->getJson('/roles/999999');
        $response->assertStatus(404);
    }

    /**
     * Test GET /roles returns a list of roles in JSON:API format.
     */
    public function testCanGetRolesListJsonapi(): void
    {
        $user = entity(User::class)->create();
        $this->actingAs($user);
        // Create multiple roles
        $roles = entity(Role::class, 3)->create();

        $this->actingAsAdmin();
        $response = $this->getJson('/roles');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['type', 'id', 'attributes' => ['name', 'permissions']],
            ],
        ]);
    }
}
