<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_user_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'support-test@example.com',
            'password' => 'SecureTestPassword123!',
            'role' => 'support',
        ]);

        $response = $this->post('/login', [
            'email' => 'support-test@example.com',
            'password' => 'SecureTestPassword123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'support-test@example.com',
            'password' => 'SecureTestPassword123!',
            'role' => 'support',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'support-test@example.com',
            'password' => 'WrongPassword123!',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
