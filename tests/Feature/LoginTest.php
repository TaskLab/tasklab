<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginWithCredentials(): void
    {

        $user = User::factory()->create();
        $p = $user->password;
        $user->password = Hash::make($user->password);
        $user->save();

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => $p
        ]);

        $props = $response->getOriginalContent()->getData()['page']['props'];

        $this->assertArrayHasKey('success', $props);
        $this->assertTrue($props['success'] === 'User credentials valid.');
        $response->assertStatus(200);
    }

    public function testLoginWithoutPassword(): void
    {
        $user = User::factory()->make();
        $response = $this->json('POST', '/login', [
            'email' => $user->email
        ]);

        $props = $response->getOriginalContent()->getData()['page']['props'];

        $this->assertArrayHasKey('error', $props);
        $this->assertTrue($props['error'] === 'The password field is required.');
        $response->assertStatus(200);
    }

    public function testLoginWithoutEmail(): void
    {
        $user = User::factory()->make();
        $response = $this->json('POST', '/login', [
            'password' => $user->password
        ]);

        $props = $response->getOriginalContent()->getData()['page']['props'];

        $this->assertArrayHasKey('error', $props);
        $this->assertTrue($props['error'] === 'The email field is required.');
        $response->assertStatus(200);
    }
}
