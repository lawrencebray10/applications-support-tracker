<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_support_user_can_record_an_activity_update(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $activity = Activity::create([
            'title' => 'Check daily SMS count',
            'description' => 'Compare SMS count against system logs.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(
            route('activity-logs.store', $activity),
            [
                'activity_date' => '2026-10-09',
                'status' => 'done',
                'remark' => 'SMS count reconciled with system logs.',
            ]
        );

        $response->assertRedirect(route('activity-logs.index'));

        $this->assertDatabaseHas('activity_logs', [
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-09',
            'status' => 'done',
            'remark' => 'SMS count reconciled with system logs.',
        ]);
    }

    public function test_multiple_updates_are_preserved_in_activity_history(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $activity = Activity::create([
            'title' => 'Check application logs',
            'description' => 'Review application logs for errors.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        $this->actingAs($user)->post(
            route('activity-logs.store', $activity),
            [
                'activity_date' => '2026-10-09',
                'status' => 'pending',
                'remark' => 'Investigation not yet completed.',
            ]
        );

        $this->actingAs($user)->post(
            route('activity-logs.store', $activity),
            [
                'activity_date' => '2026-10-09',
                'status' => 'done',
                'remark' => 'Investigation completed.',
            ]
        );

        $this->assertDatabaseCount('activity_logs', 2);

        $this->assertDatabaseHas('activity_logs', [
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'remark' => 'Investigation not yet completed.',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'status' => 'done',
            'remark' => 'Investigation completed.',
        ]);
    }

    public function test_guest_cannot_record_an_activity_update(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $activity = Activity::create([
            'title' => 'Check daily SMS count',
            'description' => 'Compare SMS count against system logs.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        $response = $this->post(
            route('activity-logs.store', $activity),
            [
                'activity_date' => '2026-10-09',
                'status' => 'done',
                'remark' => 'Completed.',
            ]
        );

        $response->assertRedirect('/login');

        $this->assertDatabaseCount('activity_logs', 0);
    }

    public function test_activity_update_rejects_an_invalid_status(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $activity = Activity::create([
            'title' => 'Check daily SMS count',
            'description' => 'Compare SMS count against system logs.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->from(route('activity-logs.create', $activity))
            ->post(route('activity-logs.store', $activity), [
                'activity_date' => '2026-10-09',
                'status' => 'invalid-status',
                'remark' => 'Test update.',
            ]);

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseCount('activity_logs', 0);
    }
}