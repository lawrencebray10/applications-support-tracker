
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management | ASAT</title>

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
            flex-wrap: wrap;
            gap: 16px;
            padding: 18px 6%;
            background: #0f172a;
            color: white;
        }

        header a { color: white; text-decoration: none; }

        .container {
            width: min(1100px, 90%);
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

        h1 { margin: 0 0 8px; font-size: 28px; }
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

        .secondary { background: #475569; }

        .card {
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        .success {
            margin-bottom: 20px;
            padding: 13px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
        }

        .table-wrapper { overflow-x: auto; }

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

        td { font-size: 14px; line-height: 1.5; }

        .role {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: bold;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 600px) {
            header { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('dashboard') }}">ASAT | Support Tracker</a>

        <div>
            {{ auth()->user()->name }}
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button class="button secondary" type="submit">Sign out</button>
            </form>
        </div>
    </header>

    <main class="container">
        <div class="heading">
            <div>
                <h1>Staff Management</h1><a href="{{ route('users.create') }}">+ Create Staff Account</a>
                <p class="muted">View staff accounts and maintain their profile details.</p>
            </div>

            <a class="button secondary" href="{{ route('dashboard') }}">Dashboard</a>
        </div>

        @if (session('success'))
            <div class="success" role="status">{{ session('success') }}</div>
        @endif

        <section class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Employee ID</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Access Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong><br>
                                    <span class="muted">{{ $user->email }}</span>
                                </td>
                                <td>{{ $user->employee_id ?: 'Not provided' }}</td>
                                <td>{{ $user->job_title ?: 'Not provided' }}</td>
                                <td>{{ $user->department ?: 'Not provided' }}</td>
                                <td><span class="role">{{ ucfirst($user->role) }}</span></td>
                                <td>
                                    <a class="button" href="{{ route('users.edit', $user) }}">
                                        Edit Profile
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty">No staff accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
