
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Handover | ASAT</title>

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
            width: min(1200px, 90%);
            margin: 36px auto;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .muted {
            color: #64748b;
        }

        .button {
            display: inline-block;
            padding: 10px 14px;
            border: 0;
            border-radius: 7px;
            background: #0f766e;
            color: white;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .button-secondary {
            background: #475569;
        }

        .filter-card, .table-card {
            margin-bottom: 22px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        .filter-form {
            display: flex;
            align-items: end;
            flex-wrap: wrap;
            gap: 12px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: bold;
        }

        input {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font: inherit;
        }

        .success {
            margin-bottom: 20px;
            padding: 13px 15px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
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
            white-space: nowrap;
        }

        .status-done {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-in-progress {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .empty-state {
            padding: 36px;
            color: #64748b;
            text-align: center;
        }

        .remark {
            min-width: 220px;
            max-width: 400px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
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

            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button class="button button-secondary" type="submit">
                    Sign out
                </button>
            </form>
        </div>
    </header>

    <main class="container">
        <div class="heading">
            <div>
                <h1>Daily Handover</h1>
                <p class="muted">
                    Review activity updates, staff remarks, and submission times.
                </p>
            </div>

            <a class="button" href="{{ route('activities.index') }}">
                View Activities
            </a>
        </div>

        @if (session('success'))
            <div class="success" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="filter-card">
            <form class="filter-form" method="GET" action="{{ route('activity-logs.index') }}">
                <div>
                    <label for="date">Activity date</label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ $date }}"
                        required
                    >
                </div>

                <button class="button" type="submit">View Date</button>

                <a class="button button-secondary" href="{{ route('activity-logs.index') }}">
                    Today
                </a>
            </form>
        </section>

        <section class="table-card">
            <h2>Updates for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h2>

            <p class="muted">
                {{ $logs->count() }} update(s) recorded for this date.
            </p>

            
            <h2 style="margin-top: 28px;">Latest Status by Activity</h2>

            <p class="muted">
                The most recent update recorded for each activity on the selected date.
            </p>

            <div class="table-wrapper" style="margin-bottom: 30px;">
                <table>
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Latest Status</th>
                            <th>Last Updated By</th>
                            <th>Time</th>
                            <th>Latest Remark</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($latestLogs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->activity->title }}</strong>
                                </td>

                                <td>
                                    <span class="status
                                        @if ($log->status === 'done') status-done
                                        @elseif ($log->status === 'pending') status-pending
                                        @else status-in-progress
                                        @endif
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $log->status)) }}
                                    </span>
                                </td>

                                <td>{{ $log->user->name }}</td>

                                <td>{{ $log->created_at->format('H:i:s') }}</td>

                                <td class="remark">
                                    {{ $log->remark ?: 'No remarks provided.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    No activity updates to summarise for this date.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h2>Complete Update History</h2>

            <p class="muted">
                All updates are retained below in chronological order for audit and handover purposes.
            </p>


            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Time Submitted</th>
                            <th>Activity</th>
                            <th>Staff Member</th>
                            <th>Status</th>
                            <th>Remarks / Handover Notes</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>
                                    {{ $log->created_at->format('H:i:s') }}
                                </td>

                                <td>
                                    <strong>{{ $log->activity->title }}</strong>
                                </td>

                                <td>
                                    {{ $log->user->name }}
                                    <br>
                                    <span class="muted">{{ $log->user->email }}</span>
                                </td>

                                <td>
                                    <span class="status
                                        @if ($log->status === 'done') status-done
                                        @elseif ($log->status === 'pending') status-pending
                                        @else status-in-progress
                                        @endif
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $log->status)) }}
                                    </span>
                                </td>

                                <td class="remark">
                                    {{ $log->remark ?: 'No remarks provided.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <strong>No updates recorded for this date.</strong>
                                    <p>Select another date or record a new activity update.</p>
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
