<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:all,active,inactive'],
        ]);

        $status = $validated['status'] ?? 'all';

        $activities = Activity::query()
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->latest()
            ->get();

        return view('activities.index', compact('activities', 'status'));
    }


    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;

        Activity::create($validated);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function deactivate(Activity $activity)
    {
        $activity->update(['is_active' => false]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity deactivated successfully.');
    }
}