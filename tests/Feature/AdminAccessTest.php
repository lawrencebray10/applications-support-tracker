<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_support_user_cannot_access_user_management(): void
    {
        $supportUser = User::factory()->create([
            'role' => 'support',
        ]);

        $this->actingAs($supportUser)
            ->get(route('users.index'))
            ->assertForbidden();

        $this->actingAs($supportUser)
            ->get(route('users.create'))
            ->assertForbidden();
    }

    public function test_support_user_cannot_create_activities(): void
    {
        $supportUser = User::factory()->create([
            'role' => 'support',
        ]);

        $this->actingAs($supportUser)
            ->get(route('activities.create'))
            ->assertForbidden();

        $this->actingAs($supportUser)
            ->post(route('activities.store'), [
                'title' => 'Unauthorized activity',
                'description' => 'This should not be created.',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('activities', [
            'title' => 'Unauthorized activity',
        ]);
    }

    public function test_admin_can_access_user_and_activity_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('activities.create'))
            ->assertOk();
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get(route('users.index'))
            ->assertRedirect('/login');

        $this->get(route('activities.create'))
            ->assertRedirect('/login');
    }

    public function test_support_user_can_still_view_activities_and_reports(): void
    {
        $supportUser = User::factory()->create([
            'role' => 'support',
        ]);

        Activity::create([
            'title' => 'Daily SMS count check',
            'description' => 'Compare SMS count against logs.',
            'created_by' => $supportUser->id,
            'is_active' => true,
        ]);

        $this->actingAs($supportUser)
            ->get(route('activities.index'))
            ->assertOk();

        $this->actingAs($supportUser)
            ->get(route('reports.index'))
            ->assertOk();
    }
}