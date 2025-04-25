<?php

namespace Tests\JsonApi;

use App\Entities\User;
use App\Entities\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test authorization rules for various user endpoints.
     * Checks for both unauthenticated (401) and forbidden (403) access.
     */
    public function testAuthorizationRules(): void
    {
        $user = entity(User::class)->create();
        $otherUser = entity(User::class)->create(); // User for testing forbidden access
        $role = entity(Role::class)->create(); // Role for relationship checks

        // URLs and Data
        $userUrl = "/users/{$user->getId()}";
        $rolesUrl = "{$userUrl}/relationships/roles";
        $roleData = ['data' => [['type' => 'roles', 'id' => (string) $role->getId()]]];

        // 1. Test Unauthenticated Access (401)
        $this->getJson($userUrl)->assertStatus(401);
        $this->getJson('/users/me')->assertStatus(401);
        $this->patchJson($userUrl, [])->assertStatus(401);
        $this->deleteJson($userUrl)->assertStatus(401);
        $this->getJson('/users')->assertStatus(401); // List users requires auth (admin only)
        $this->getJson($rolesUrl)->assertStatus(401); // View roles relationship
        $this->postJson($rolesUrl, $roleData)->assertStatus(401); // Add role relationship
        $this->patchJson($rolesUrl, $roleData)->assertStatus(401); // Replace role relationship
        $this->deleteJson($rolesUrl, $roleData)->assertStatus(401); // Delete role relationship

        // 2. Test Forbidden Access (403) - Acting as $otherUser
        $this->actingAs($otherUser);

        // Cannot view a different user (unless admin)
        $this->getJson($userUrl)->assertStatus(403);
        // Cannot update a different user
        $this->patchJson($userUrl, [])->assertStatus(403);
        // Cannot delete a different user
        $this->deleteJson($userUrl)->assertStatus(403);
        // Cannot list users (regular user)
        $this->getJson('/users')->assertStatus(403);
        // Cannot view/manage other user's roles
        $this->getJson($rolesUrl)->assertStatus(403);
        $this->postJson($rolesUrl, $roleData)->assertStatus(403);
        $this->patchJson($rolesUrl, $roleData)->assertStatus(403);
        $this->deleteJson($rolesUrl, $roleData)->assertStatus(403);

        // 3. Test Allowed Access (Acting as $user for self-actions)
        $this->actingAs($user);
        $this->getJson($userUrl)->assertOk(); // Can view self
        $this->getJson('/users/me')->assertOk(); // Can view 'me'

        // Update and delete require specific tests with data and assertions,
        // so they are better handled in dedicated test methods.
    }

    public function testNotAuthenticated(): void
    {
        $this->getJson('/users/me')->assertStatus(401);
    }

    /**
     * Test GET /users/{id} returns JSON:API compliant response for existing user.
     */
    public function testCanGetUserByIdJsonapi(): void
    {
        // Create a user entity using Doctrine factory
        $user = entity(User::class)->create();

        $this->actingAs($user);

        // Perform GET request to JSON:API endpoint
        $response = $this->getJson("/users/{$user->getId()}");

        // Assert response is successful
        $response->assertStatus(200);

        // Assert JSON:API structure
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'email',
                    // Add other User attributes exposed by your JSON:API resource
                ],
            ],
        ]);

        // Assert correct data is returned
        $response->assertJson([
            'data' => [
                'type' => 'users',
                'id' => (string) $user->getId(),
                'attributes' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                ],
            ],
        ]);
    }

    /**
     * Test GET /users/{id} returns 404 for non-existent user.
     */
    public function testGetUserByIdReturns404ForMissingUser(): void
    {
        $this->actingAsAdmin();
        $response = $this->getJson('/users/999999');
        $response->assertStatus(404);
    }

    /**
     * Test GET /users returns a list of users in JSON:API format.
     */
    public function testCanListUsersJsonapi(): void
    {
        $users = entity(User::class, 3)->create();

        $this->actingAs(entity(User::class, 'admin')->create());

        $response = $this->getJson('/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                    ],
                ],
            ],
        ]);
        $ids = array_map(fn($u) => (string)$u->getId(), $users->all());
        foreach ($ids as $id) {
            $response->assertJsonFragment(['id' => $id]);
        }
    }

    /**
     * Test POST /users creates a new user and returns JSON:API resource.
     */
    public function testCanCreateUserJsonapi(): void
    {
        $payload = [
            'data' => [
                'type' => 'users',
                'attributes' => [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => 'secret123',
                ],
            ],
        ];

        $response = $this->postJson('/users', $payload);

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $location = $response->headers->get('Location');
        $this->assertNotEmpty($location);
        $id = $response->json('data.id');
        $this->assertStringContainsString("/users/{$id}", $location);

        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'email',
                ],
            ],
        ]);

        $response->assertJsonFragment([
            'type' => 'users',
            'attributes' => [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $id,
        ]);
    }

    /**
     * Test PATCH /users/{id} updates user attributes.
     */
    public function testCanUpdateUserJsonapi(): void
    {
        $user = entity(User::class)->create();
        $payload = [
            'data' => [
                'type' => 'users',
                'id' => (string)$user->getId(),
                'attributes' => [
                    'name' => 'Updated Name',
                ],
            ],
        ];
        $this->actingAs($user);
        $response = $this->patchJson("/users/{$user->getId()}", $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'email',
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'id' => (string)$user->getId(),
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Test PATCH /users/{id} fails validation for invalid email.
     */
    public function testUpdateUserFailsWithInvalidEmail(): void
    {
        $user = entity(User::class)->create();
        $payload = [
            'data' => [
                'type' => 'users',
                'id' => (string)$user->getId(),
                'attributes' => [
                    'email' => 'not-an-email',
                ],
            ],
        ];
        $this->actingAs($user);
        $response = $this->patchJson("/users/{$user->getId()}", $payload);
        $response->assertStatus(422);
        $this->assertJsonApiValidationErrors($response, ['/data/attributes/email']);
    }

    /**
     * Test PATCH /users/{id} fails validation for short password.
     */
    public function testUpdateUserFailsWithShortPassword(): void
    {
        $user = entity(User::class)->create();
        $payload = [
            'data' => [
                'type' => 'users',
                'id' => (string)$user->getId(),
                'attributes' => [
                    'password' => '123',
                ],
            ],
        ];
        $this->actingAs($user);
        $response = $this->patchJson("/users/{$user->getId()}", $payload);
        $response->assertStatus(422);
        $this->assertJsonApiValidationErrors($response, ['/data/attributes/password']);
    }

    /**
     * Test PATCH /users/{id} fails validation for too long name.
     */
    public function testUpdateUserFailsWithLongName(): void
    {
        $user = entity(User::class)->create();
        $payload = [
            'data' => [
                'type' => 'users',
                'id' => (string)$user->getId(),
                'attributes' => [
                    'name' => str_repeat('a', 256),
                ],
            ],
        ];
        $this->actingAs($user);
        $response = $this->patchJson("/users/{$user->getId()}", $payload);
        $response->assertStatus(422);
        $this->assertJsonApiValidationErrors($response, ['/data/attributes/name']);
    }

    /**
     * Test DELETE /users/{id} deletes the user.
     */
    public function testCanDeleteUserJsonapi(): void
    {
        $user = entity(User::class)->create();
        $userId = $user->getId(); // Capture ID before deletion
        $this->actingAs($user);
        $response = $this->deleteJson("/users/{$userId}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * Test GET /users/me returns the current user's profile.
     */
    public function testCanGetCurrentUserProfile(): void
    {
        $this->actingAs($user = entity(User::class)->create());

        $response = $this->getJson('/users/me');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'type' => 'users',
                'id' => (string) $user->getId(),
                'attributes' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                ],
            ],
        ]);
    }

    /**
     * Test GET /users/me returns 401 Unauthorized when the user is not authenticated.
     */
    public function testCannotGetCurrentUserProfileWhenNotAuthenticated(): void
    {
        $response = $this->getJson('/users/me');
        $response->assertStatus(401);
    }

    /**
     * Test PATCH /users/{id} allows a user to update their own profile.
     */
    public function testUserCanUpdateSelf(): void
    {
        $user = entity(User::class)->create();
        $this->actingAs($user);

        $newName = 'Updated Name';
        $updateData = [
            'data' => [
                'type' => 'users',
                'id' => (string) $user->getId(),
                'attributes' => [
                    'name' => $newName,
                ],
            ],
        ];

        $response = $this->patchJson("/users/{$user->getId()}", $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('data.attributes.name', $newName);

        // Optional: Verify change in DB
        $this->em()->refresh($user);
        $this->assertEquals($newName, $user->getName());
    }

    /**
     * Test DELETE /users/{id} allows a user to delete their own account.
     */
    public function testUserCanDeleteSelf(): void
    {
        $user = entity(User::class)->create();
        $userId = $user->getId();
        $this->actingAs($user);

        $response = $this->deleteJson("/users/{$userId}");

        $response->assertStatus(204);

        // Verify user is deleted from DB
        $deletedUser = $this->em()->find(User::class, $userId);
        $this->assertNull($deletedUser, 'User should be deleted from database.');
    }

    /**
     * Test GET /users allows an admin to list all users.
     */
    public function testAdminCanListUsers(): void
    {
        // Create some users
        entity(User::class, 2)->create();
        $admin = entity(User::class, 'admin')->create(); // Ensure admin factory state exists

        $this->actingAs($admin);

        $response = $this->getJson('/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => ['name', 'email'], // Adjust based on your UserTransformer
                    ]
                ],
            ])
            ->assertJsonCount(3, 'data'); // 2 users + 1 admin
    }

    /**
     * Test GET /users/{id}/relationships/roles allows a user to view their own roles.
     */
    public function testUserCanViewOwnRolesRelationship(): void
    {
        $user = entity(User::class)->create();
        $role = entity(Role::class)->create();
        $user->addRole($role);
        $this->em()->flush();

        $this->actingAs($user);

        $url = "/users/{$user->getId()}/relationships/roles";
        $this->getJson($url)
            ->assertOk()
            ->assertJsonStructure(['data' => [['type', 'id']]])
            ->assertJsonPath('data.0.id', (string) $role->getId());
    }

    /**
     * Test Admin can manage roles via /users/{id}/relationships/roles.
     */
    public function testAdminCanManageUserRoles(): void
    {
        $admin = entity(User::class, 'admin')->create();
        $user = entity(User::class)->create();
        $role1 = entity(Role::class)->create(['name' => 'Role1']);
        $role2 = entity(Role::class)->create(['name' => 'Role2']);
        $role3 = entity(Role::class)->create(['name' => 'Role3']);

        $url = "/users/{$user->getId()}/relationships/roles";

        $this->actingAs($admin);

        // Add role1 (POST)
        $addData = ['data' => [['type' => 'roles', 'id' => (string) $role1->getId()]]];
        $this->postJson($url, $addData)->assertOk(); // Expect 200 OK

        $this->em()->refresh($user);
        $this->assertTrue($user->hasRole($role1));
        $this->assertCount(1, $user->getRoles());

        // Replace with role2 (PATCH)
        $replaceData = ['data' => [['type' => 'roles', 'id' => (string) $role2->getId()]]];
        $this->patchJson($url, $replaceData)->assertOk(); // Expect 200 OK

        $this->em()->refresh($user);
        $this->assertFalse($user->hasRole($role1));
        $this->assertTrue($user->hasRole($role2));
        $this->assertCount(1, $user->getRoles());

        // Add role3 (POST - should add alongside role2)
        $addMoreData = ['data' => [['type' => 'roles', 'id' => (string) $role3->getId()]]];
        $this->postJson($url, $addMoreData)->assertOk(); // Expect 200 OK

        $this->em()->refresh($user);
        $this->assertTrue($user->hasRole($role2));
        $this->assertTrue($user->hasRole($role3));
        $this->assertCount(2, $user->getRoles());

        // Remove role2 (DELETE)
        $removeData = ['data' => [['type' => 'roles', 'id' => (string) $role2->getId()]]];
        $this->deleteJson($url, $removeData)->assertStatus(204); // Expect 204 No Content for DELETE

        $this->em()->refresh($user);
        $this->assertFalse($user->hasRole($role2));
        $this->assertTrue($user->hasRole($role3));
        $this->assertCount(1, $user->getRoles());

        // View roles (GET)
        $this->getJson($url)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', (string) $role3->getId());
    }
}
