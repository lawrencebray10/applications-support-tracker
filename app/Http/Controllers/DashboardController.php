<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $activeActivities = Activity::where('is_active', true)->count();

        $todayUpdates = ActivityLog::whereDate('activity_date', $today)
            ->count();

        $completedToday = ActivityLog::whereDate('activity_date', $today)
            ->where('status', 'done')
            ->count();

        $pendingToday = ActivityLog::whereDate('activity_date', $today)
            ->where('status', 'pending')
            ->count();

            
        $inProgressToday = ActivityLog::whereDate('activity_date', $today)
            ->where('status', 'in_progress')
            ->count();


        $recentLogs = ActivityLog::with(['activity', 'user'])
            ->whereDate('activity_date', $today)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'activeActivities',
            'todayUpdates',
            'completedToday',
            'pendingToday',
            'inProgressToday',
            'recentLogs',
            'today'
        ));
    }
}
