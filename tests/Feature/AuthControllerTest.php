<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Entities\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = entity(User::class)->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function testLoginWithValidCredentials(): void
    {
        // Use the user created in setUp()
        $response = $this->postJson('/auth/login', [
            'email' => $this->user->getEmail(),
            'password' => 'password', // The password set in setUp()
        ]);

        $response->assertOk();
        $response->assertJson(['id' => $this->user->getId()]);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        // Use the user from setUp() and attempt login with a wrong password
        $response = $this->postJson('/auth/login', [
            'email' => $this->user->getEmail(), // Email from setUp() user
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function testRequestResetPasswordWithValidEmail(): void
    {
        $user = entity(User::class)->create([
            'email' => 'reset@example.com',
        ]);

        $response = $this->postJson('/auth/request-reset-password', [
            'email' => 'reset@example.com',
        ]);

        $response->assertOk();
        $response->assertJson(['status' => true]);
    }

    public function testRequestResetPasswordWithInvalidEmail(): void
    {
        $response = $this->postJson('/auth/request-reset-password', [
            'email' => 'notfound@example.com',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['status' => 'We can\'t find a user with that email address.']);
    }

    public function testResetPasswordWithValidToken(): void
    {
        $user = entity(User::class)->create([
            'email' => 'reset2@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        // Simulate a password reset token
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->postJson('/auth/reset-password', [
            'email' => 'reset2@example.com',
            'password' => 'newpassword123',
            'token' => $token,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['status']);
        $this->assertTrue(Hash::check('newpassword123', $user->getPassword()));
    }

    public function testLogout(): void
    {
        $user = entity(User::class)->create([
            'email' => 'logout@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/auth/logout');

        $response->assertStatus(204); // Expect 204 No Content
        $this->assertGuest(); // Ensure user is logged out
    }

    public function testCanCreateTokenAndAuthenticate(): void
    {
        $deviceName = 'test-device';

        // 1. Create Token
        $response = $this->postJson(route('auth.token'), [
            'email' => $this->user->getEmail(),
            'password' => 'password', // Plain text password
            'deviceName' => $deviceName,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token'])
            ->assertJsonMissingPath('errors');

        $token = $response->json('token');
        $this->assertNotEmpty($token);

        // 2. Authenticate with Token
        // Assuming '/api/users/me' is a sanctum protected route
        $authResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/vnd.api+json',
        ])->getJson('/api/users/me');

        $authResponse->assertStatus(200)
            ->assertJsonPath('data.attributes.email', $this->user->getEmail());
    }

    public function testCannotCreateTokenWithInvalidCredentials(): void
    {
        $response = $this->postJson(route('auth.token'), [
            'email' => $this->user->getEmail(),
            'password' => 'wrong-password',
            'deviceName' => 'test-device',
        ]);

        $response->assertStatus(422) // Laravel validation exception for auth.failed
            ->assertJsonValidationErrors(['email' => __('auth.failed')]);
    }

    public function testCannotCreateTokenWithoutDeviceName(): void
    {
        $response = $this->postJson(route('auth.token'), [
            'email' => $this->user->getEmail(),
            'password' => 'password',
            // 'deviceName' is missing
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['deviceName']);
    }
}
