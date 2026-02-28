<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_resource_is_secured()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/users/me');

        $response->assertStatus(200)
                 ->assertJsonMissing(['password'])
                 ->assertJsonMissing(['remember_token']);
    }

    public function test_admin_can_see_sensitive_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'client']);

        Passport::actingAs($admin);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertJsonStructure([
            'data' => ['created_at', 'updated_at']
        ]);
    }

    public function test_user_can_change_password() {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword')
        ]);

        Passport::actingAs($user);

        $response = $this->patchJson('/api/users/me/password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(200);

        $this->assertTrue(
            Hash::check('newpassword', $user->fresh()->password)
        );
    }

    public function test_user_cannot_change_password_with_wrong_current_password() {

        $user = User::factory()->create([
            'password' => bcrypt('oldpassword')
        ]);

        Passport::actingAs($user);

        $response = $this->patchJson('/api/users/me/password', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(400);
    }

    public function tst_password_confirmation_match() {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword')
        ]);

        Passport::actingAs($user);

        $response = $this->patchJson('/api/users/me/password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'different'
        ]);
        $response->assertStatus(422);
    
    }

}