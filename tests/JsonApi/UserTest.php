<?php

namespace Tests\JsonApi;

use App\Entities\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test GET /users/{id} returns JSON:API compliant response for existing user.
     */
    public function testCanGetUserByIdJsonapi(): void
    {
        // Create a user entity using Doctrine factory
        $user = entity(User::class)->create();

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
        $response = $this->getJson('/users/999999');
        $response->assertStatus(404);
    }

    /**
     * Test GET /users returns a list of users in JSON:API format.
     */
    public function testCanListUsersJsonapi(): void
    {
        $users = entity(User::class, 3)->create();

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
        $this->actingAs($user);
        $userId = $user->getId(); // Capture ID before deletion
        $response = $this->deleteJson("/users/{$userId}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * Test GET /users/me returns the current user's profile.
     */
    public function testCanGetCurrentUserProfile(): void
    {
        $user = entity(User::class)->create();
        $this->actingAs($user);
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
}
