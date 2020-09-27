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
    public function testLoginAndAuth(): void
    {

        $user = User::factory()->create();
        $p = $user->password;
        $user->password = Hash::make($user->password);
        $user->save();

        $response = $this->actingAs($user)->json('POST', '/login', [
            'email' => $user->email,
            'password' => $p ]
        );
        $response->assertStatus(302);

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => $user->password ]
        );
        $response->assertStatus(401);

        $response = $this->json('POST', '/login', [
            'email' => $user->email]
        );
        $response->assertStatus(422);
    }
}
