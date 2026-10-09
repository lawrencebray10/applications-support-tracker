<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function create(Activity $activity)
    {
        abort_unless($activity->is_active, 404);

        return view('activity-logs.create', compact('activity'));
    }

    public function store(Request $request, Activity $activity)
    {
        abort_unless($activity->is_active, 404);

        $validated = $request->validate([
            'activity_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,in_progress,done'],
            'remark' => ['nullable', 'string', 'max:5000'],
        ]);

        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => Auth::id(),
            'activity_date' => $validated['activity_date'],
            'status' => $validated['status'],
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('activity-logs.index')
            ->with('success', 'Activity update recorded successfully.');
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        $logs = ActivityLog::with(['activity', 'user'])
            ->whereDate('activity_date', $date)
            ->orderBy('created_at')
            ->get();

            
        $latestLogs = ActivityLog::with(['activity', 'user'])
            ->whereDate('activity_date', $date)
            ->whereIn('id', function ($query) use ($date) {
                $query->selectRaw('MAX(id)')
                    ->from('activity_logs')
                    ->whereDate('activity_date', $date)
                    ->groupBy('activity_id');
            })
            ->orderBy('activity_id')
            ->get();


        return view('activity-logs.index', compact('logs', 'latestLogs', 'date'));
    }
}