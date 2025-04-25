<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Entities\User;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testLoginWithValidCredentials(): void
    {
        $user = entity(User::class)->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk();
        $response->assertJson(['id' => $user->getId()]);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        entity(User::class)->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/auth/login', [
            'email' => 'test@example.com',
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

        $this->be($user);
        $response = $this->postJson('/auth/logout');
        $response->assertOk();
        $response->assertJson(['status' => true]);
    }
}
