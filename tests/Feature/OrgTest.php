<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\{
    Organization,
    User
};

use Illuminate\Support\Facades\{
    Auth,
    Hash
};

class OrgTest extends TestCase
{
    protected $payload = [
        'org_name' => 'Test Org'
    ];

    /**
     * Testing user trying to create an org when 
     *   they are not authenticated.
     *
     * @return void
     */
    public function testFailedAuthOrgCreation(): void 
    {
        $response = $this->json(
            'POST', 
            '/org/create', 
            $this->payload
        );
        $response->assertStatus(401);
    }

    /**
     * Test user creating org with invalid payload
     *
     * @return void
     */
    public function testInvalidPayloadOrgCreation(): void 
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json(
            'POST', 
            '/org/create', 
            []
        );
        $response->assertStatus(422);
    }

    /**
     * Test user creating org if User is already in an Org
     *
     * @return void
     */
    public function testAlreadyInOrgOrgCreation(): void 
    {
        $user = User::factory()->create();
        $user->organization_id = 1;
        $user->save();

        $response = $this->actingAs($user)->json(
            'POST', 
            '/org/create', 
            $this->payload
        );
        $response->assertStatus(403);
    }

    /**
     * Test a valid org creation.
     *
     * @return void
     */
    public function testInitialOrgCreation(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->json(
            'POST', 
            '/org/create', 
            $this->payload
        );
        $response->assertStatus(200);

        $org = Organization::where('org_name', $this->payload['org_name'])->exists();
        $this->assertTrue($org);
    }

    /**
     * Test disabling an org
     *
     * @return void
     */
    public function testDisablingOrg(): void 
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json(
            'GET', 
            "/org/delete/{$org->id}"
        );
        $response->assertStatus(200);
    }

}
