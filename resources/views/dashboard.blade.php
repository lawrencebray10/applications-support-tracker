
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | ASAT</title>

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

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding: 18px 6%;
            background: #0f172a;
            color: white;
        }

        .brand {
            font-size: 18px;
            font-weight: bold;
        }

        .topbar a {
            color: white;
            text-decoration: none;
        }

        .user-info {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }

        .user-info span {
            color: #cbd5e1;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 7px;
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

        .container {
            width: min(1200px, 90%);
            margin: 36px auto;
        }

        .welcome {
            margin-bottom: 28px;
        }

        .welcome h1 {
            margin: 0 0 8px;
            font-size: 29px;
        }

        .welcome p {
            margin: 0;
            color: #64748b;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            padding: 22px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        .stat-label {
            margin: 0 0 12px;
            color: #64748b;
            font-size: 13px;
        }

        .stat-value {
            margin: 0;
            font-size: 30px;
            font-weight: bold;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-heading h2 {
            margin: 0;
            font-size: 20px;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .link-card {
            display: block;
            padding: 22px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            color: inherit;
            text-decoration: none;
        }

        .link-card:hover {
            border-color: #0f766e;
        }

        .link-card h3 {
            margin: 0 0 9px;
            font-size: 16px;
        }

        .link-card p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        .table-card {
            padding: 22px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
        }

        td {
            font-size: 14px;
            line-height: 1.5;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .done {
            background: #dcfce7;
            color: #166534;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .in-progress {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .empty {
            padding: 28px;
            color: #64748b;
            text-align: center;
        }

        .muted {
            color: #64748b;
        }

        @media (max-width: 600px) {
            .container {
                margin: 24px auto;
            }

            .welcome h1 {
                font-size: 24px;
            }

            .table-card {
                padding: 14px;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}">
            ASAT | Support Tracker
        </a>

        @if (auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}">Staff Management</a>
        @endif

        <div class="user-info">
            <span>
                {{ auth()->user()->name }}
                ({{ ucfirst(auth()->user()->role) }})
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="button button-secondary" type="submit">
                    Sign out
                </button>
            </form>
        </div>
    </header>

    <main class="container">
        <section class="welcome">
            <h1>Good day, {{ auth()->user()->name }}</h1>
            <p>
                Here is your support team's activity overview for
                {{ \Carbon\Carbon::parse($today)->format('l, d F Y') }}.
            </p>
        </section>

        <section class="stats">
            <article class="stat-card">
                <p class="stat-label">Active Activities</p>
                <p class="stat-value">{{ $activeActivities }}</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Today's Updates</p>
                <p class="stat-value">{{ $todayUpdates }}</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Completed Updates</p>
                <p class="stat-value">{{ $completedToday }}</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Pending Updates</p>
                <p class="stat-value">{{ $pendingToday }}</p>
            </article>
            
            <article class="stat-card">
                <p class="stat-label">In-Progress Updates</p>
                <p class="stat-value">{{ $inProgressToday }}</p>
            </article>

        </section>

        <section>
            <div class="section-heading">
                <h2>Quick Access</h2>
            </div>

            <div class="quick-links">
                <a class="link-card" href="{{ route('activities.index') }}">
                    <h3>Support Activities</h3>
                    <p>View support tasks and record daily status updates.</p>
                </a>

                <a class="link-card" href="{{ route('activity-logs.index') }}">
                    <h3>Daily Handover</h3>
                    <p>Review staff updates, timestamps and handover remarks.</p>
                </a>

                <a class="link-card" href="{{ route('reports.index') }}">
                    <h3>Activity Reports</h3>
                    <p>Filter activity history by a custom date range.</p>
                </a>
            </div>
        </section>

        <section class="table-card">
            <div class="section-heading">
                <h2>Recent Updates Today</h2>

                <a class="button" href="{{ route('activity-logs.index') }}">
                    View Handover
                </a>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Activity</th>
                            <th>Staff Member</th>
                            <th>Status</th>
                            <th>Remark</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentLogs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('H:i:s') }}</td>
                                <td>{{ $log->activity->title }}</td>
                                <td>{{ $log->user->name }}</td>
                                <td>
                                    <span class="status
                                        @if ($log->status === 'done') done
                                        @elseif ($log->status === 'pending') pending
                                        @else in-progress
                                        @endif
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $log->status)) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $log->remark ?: 'No remarks provided.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">
                                    No updates recorded for today yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
