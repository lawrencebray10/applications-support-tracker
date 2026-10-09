<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'] ?? now()->toDateString();
        $endDate = $validated['end_date'] ?? $startDate;

        $logs = ActivityLog::with(['activity', 'user'])
            ->whereDate('activity_date', '>=', $startDate)
            ->whereDate('activity_date', '<=', $endDate)
            ->orderBy('activity_date')
            ->orderBy('created_at')
            ->get();

        
        $totalUpdates = $logs->count();

        $totalActivities = $logs->pluck('activity_id')->unique()->count();

        $completedUpdates = $logs->where('status', 'done')->count();
        $pendingUpdates = $logs->where('status', 'pending')->count();
        $inProgressUpdates = $logs->where('status', 'in_progress')->count();


        return view('reports.index', compact(
            'logs',
            'startDate',
            'endDate',
            'totalUpdates',
            'totalActivities',
            'completedUpdates',
            'pendingUpdates',
            'inProgressUpdates'
        ));
    }
}



