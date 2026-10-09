<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_shows_correct_activity_update_totals(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $activityOne = Activity::create([
            'title' => 'Check SMS count',
            'description' => 'Reconcile SMS count with logs.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        $activityTwo = Activity::create([
            'title' => 'Check application logs',
            'description' => 'Review application logs.',
            'created_by' => $user->id,
            'is_active' => true,
        ]);

        ActivityLog::create([
            'activity_id' => $activityOne->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-09',
            'status' => 'done',
            'remark' => 'Completed successfully.',
        ]);

        ActivityLog::create([
            'activity_id' => $activityOne->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-09',
            'status' => 'pending',
            'remark' => 'Follow-up required.',
        ]);

        ActivityLog::create([
            'activity_id' => $activityTwo->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-09',
            'status' => 'in_progress',
            'remark' => 'Investigation ongoing.',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index', [
            'start_date' => '2026-10-09',
            'end_date' => '2026-10-09',
        ]));

        $response->assertOk()
            ->assertViewHas('totalUpdates', 3)
            ->assertViewHas('totalActivities', 2)
            ->assertViewHas('completedUpdates', 1)
            ->assertViewHas('pendingUpdates', 1)
            ->assertViewHas('inProgressUpdates', 1);
    }

    public function test_report_filters_updates_by_selected_date_range(): void
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
            'remark' => 'Previous day update.',
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-09',
            'status' => 'pending',
            'remark' => 'Selected date update.',
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => $user->id,
            'activity_date' => '2026-10-10',
            'status' => 'in_progress',
            'remark' => 'Following day update.',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index', [
            'start_date' => '2026-10-09',
            'end_date' => '2026-10-09',
        ]));

        $response->assertOk()
            ->assertViewHas('totalUpdates', 1)
            ->assertViewHas('pendingUpdates', 1)
            ->assertViewHas('logs', function ($logs) {
                return $logs->count() === 1
                    && $logs->first()->remark === 'Selected date update.';
            });
    }

    public function test_report_rejects_an_end_date_before_the_start_date(): void
    {
        $user = User::factory()->create([
            'role' => 'support',
        ]);

        $response = $this->actingAs($user)->get(route('reports.index', [
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-09',
        ]));

        $response->assertSessionHasErrors('end_date');
    }

    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get(route('reports.index'));

        $response->assertRedirect('/login');
    }
}