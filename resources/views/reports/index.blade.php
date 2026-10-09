
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Reports | ASAT</title>

    <style>
        * { box-sizing: border-box; }

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

        .heading { margin-bottom: 24px; }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .muted { color: #64748b; }

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

        .button-secondary { background: #475569; }

        .card {
            margin-bottom: 22px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        .filters {
            display: flex;
            align-items: end;
            flex-wrap: wrap;
            gap: 14px;
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

        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat {
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        .stat p {
            margin: 0 0 10px;
            color: #64748b;
            font-size: 13px;
        }

        .stat strong { font-size: 27px; }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 13px;
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

        .done { background: #dcfce7; color: #166534; }
        .pending { background: #fef3c7; color: #92400e; }
        .in-progress { background: #dbeafe; color: #1d4ed8; }

        .empty {
            padding: 30px;
            text-align: center;
            color: #64748b;
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
        <section class="heading">
            <h1>Activity Reports</h1>
            <p class="muted">
                Review support activity history over a selected date range.
            </p>

            <a class="button button-secondary" href="{{ route('activity-logs.index') }}">
                Daily Handover
            </a>
        </section>

        <section class="card">
            <form class="filters" method="GET" action="{{ route('reports.index') }}">
                <div>
                    <label for="start_date">Start date</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $startDate }}"
                        required
                    >
                </div>

                <div>
                    <label for="end_date">End date</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $endDate }}"
                        required
                    >
                </div>

                <button class="button" type="submit">Generate Report</button>
            </form>

            @if ($errors->any())
                <p role="alert" style="color:#b91c1c">
                    {{ $errors->first() }}
                </p>
            @endif
        </section>

        <section class="summary">
            <article class="stat">
                <p>Total updates</p>
                <strong>{{ $totalUpdates }}</strong>
            </article>

            
            <article class="stat">
                <p>Distinct activities</p>
                <strong>{{ $totalActivities }}</strong>
            </article>


            <article class="stat">
                <p>Completed updates</p>
                <strong>{{ $completedUpdates }}</strong>
            </article>

            <article class="stat">
                <p>Pending updates</p>
                <strong>{{ $pendingUpdates }}</strong>
            </article>

            <article class="stat">
                <p>In-progress updates</p>
                <strong>{{ $inProgressUpdates }}</strong>
            </article>
        </section>

        <section class="card">
            <h2>Report Details</h2>
            <p class="muted">
                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                –
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Activity Date</th>
                            <th>Time Submitted</th>
                            <th>Activity</th>
                            <th>Staff Member</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->activity_date->format('d M Y') }}</td>
                                <td>{{ $log->created_at->format('H:i:s') }}</td>
                                <td>{{ $log->activity->title }}</td>
                                <td>
                                    {{ $log->user->name }}
                                    <br>
                                    <span class="muted">{{ $log->user->email }}</span>
                                </td>
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
                                <td class="remark">
                                    {{ $log->remark ?: 'No remarks provided.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty">
                                    No activity updates were found for this date range.
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
