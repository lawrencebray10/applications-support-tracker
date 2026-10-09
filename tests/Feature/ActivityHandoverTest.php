<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityHandoverTest extends TestCase
{
    use RefreshDatabase;

    public function test_handover_page_displays_activity_and_staff_details(): void
    {
        $user = User::factory()->create([
            'name' => 'Handover Test Staff',
            'role' => 'support',
        ]);

        $activity = Activity::create([
            'title' => 'Check daily SMS count',
            'description' => 'Compare SMS count with system logs.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'activity_date' => now()->toDateString(),
            'status' => 'done',
            'remark' => 'SMS count reconciled successfully.',
        ]);

        $response = $this->actingAs($user)->get(route('activity-logs.index'));

        $response->assertOk()
            ->assertSee('Check daily SMS count')
            ->assertSee('Handover Test Staff')
            ->assertSee('SMS count reconciled successfully.')
            ->assertSee('done');
    }

    public function test_handover_page_filters_updates_by_date(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $activity = Activity::create([
            'title' => 'Daily system check',
            'description' => 'Check system operation.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-08',
            'status' => 'done',
            'remark' => 'Update from October 8.',
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-09',
            'status' => 'pending',
            'remark' => 'Update from October 9.',
        ]);

        $response = $this->actingAs($user)->get(
            route('activity-logs.index', ['date' => '2026-10-09'])
        );

        $response->assertOk()
            ->assertSee('Update from October 9.')
            ->assertDontSee('Update from October 8.');
    }

    public function test_guest_cannot_access_handover_page(): void
    {
        $response = $this->get(route('activity-logs.index'));

        $response->assertRedirect('/login');
    }
}