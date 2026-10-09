
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Activities | ASAT</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 6%;
            background: #0f172a;
            color: white;
        }

        header a {
            color: white;
            text-decoration: none;
        }

        .container {
            width: min(1100px, 90%);
            margin: 36px auto;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        h1 {
            margin-bottom: 8px;
            font-size: 28px;
        }

        .muted {
            color: #64748b;
        }

        .button {
            display: inline-block;
            padding: 11px 15px;
            border: 0;
            border-radius: 8px;
            background: #0f766e;
            color: white;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .button:hover {
            background: #115e59;
        }

        .button-secondary {
            background: #475569;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
        }

        .table-wrapper {
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            font-size: 13px;
            color: #475569;
        }

        td {
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .active {
            background: #dcfce7;
            color: #166534;
        }

        .inactive {
            background: #e2e8f0;
            color: #475569;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: #64748b;
        }

        .inline-form {
            display: inline;
        }

        @media (max-width: 600px) {
            header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('dashboard') }}">ASAT | Support Tracker</a>

        <div>
            <span>{{ auth()->user()->name }}</span>

            <form class="inline-form" method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="button button-secondary" type="submit">
                    Sign out
                </button>
            </form>
        </div>
    </header>

    <main class="container">
        <div class="page-heading">
            <div>
                <h1>Support Activities</h1>
                <p class="muted">
                    Manage the recurring tasks monitored by the support team.
                </p>
            </div>

            @if (auth()->user()->isAdmin())
                <a class="button" href="{{ route('activities.create') }}">
                    + Add Activity
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert" role="status">
                {{ session('success') }}
            </div>
        @endif

        
        <form method="GET" action="{{ route('activities.index') }}"
            style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">

            <label for="status" style="font-size: 14px; font-weight: bold;">
                Filter activities:
            </label>

            <select
                name="status"
                id="status"
                onchange="this.form.submit()"
                style="padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;"
            >
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                    All activities
                </option>

                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>
                    Active only
                </option>

                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>
                    Inactive only
                </option>
            </select>

            <noscript>
                <button class="button" type="submit">Apply Filter</button>
            </noscript>
        </form>


        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                        @if (auth()->user()->isAdmin())
                            <th>Management</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td>
                                <strong>{{ $activity->title }}</strong>
                            </td>

                            <td>
                                {{ $activity->description ?: 'No description provided.' }}
                            </td>

                            <td>
                                <span class="status {{ $activity->is_active ? 'active' : 'inactive' }}">
                                    {{ $activity->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                @if ($activity->is_active)
                                    <a
                                        class="button"
                                        href="{{ route('activity-logs.create', $activity) }}"
                                    >
                                        Record Update
                                    </a>
                                @else
                                    <span class="muted">Inactive activity</span>
                                @endif
                            </td>

                            @if (auth()->user()->isAdmin())
                                <td>
                                    @if ($activity->is_active)
                                        <form
                                            method="POST"
                                            action="{{ route('activities.deactivate', $activity) }}"
                                            onsubmit="return confirm('Deactivate this activity?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button class="button button-secondary" type="submit">
                                                Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <span class="muted">No actions</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}"
                                class="empty-state"
                            >
                                <strong>No activities yet</strong>
                                <p>Add your first support activity to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
