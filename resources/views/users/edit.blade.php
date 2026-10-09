
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff Profile | ASAT</title>

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
            width: min(760px, 90%);
            margin: 36px auto;
        }

        .card {
            padding: 24px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
        }

        h1 { margin: 0 0 8px; font-size: 28px; }
        .muted { color: #64748b; }

        .field { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font: inherit;
            background: white;
        }

        .hint {
            margin-top: 6px;
            font-size: 12px;
            color: #64748b;
        }

        .button {
            display: inline-block;
            padding: 11px 15px;
            border: 0;
            border-radius: 7px;
            background: #0f766e;
            color: white;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .secondary { background: #475569; }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
        }

        .errors {
            margin-bottom: 20px;
            padding: 14px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 600px) {
            header { align-items: flex-start; flex-direction: column; }
            .card { padding: 18px; }
        }
    </style>
</head>
<body>
    <header>
        <a href="{{ route('dashboard') }}">ASAT | Support Tracker</a>
        <span>{{ auth()->user()->name }} — Administrator</span>
    </header>

    <main class="container">
        <section class="card">
            <h1>Edit Staff Profile</h1>
            <p class="muted">Update account and personnel information for {{ $user->name }}.</p>

            @if ($errors->any())
                <div class="errors" role="alert">
                    <strong>Please correct the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="name">Full name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="field">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="field">
                    <label for="employee_id">Employee ID</label>
                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id', $user->employee_id) }}"
                        maxlength="100"
                    >
                    <p class="hint">Must be unique if provided.</p>
                </div>

                <div class="field">
                    <label for="job_title">Job title</label>
                    <input
                        type="text"
                        id="job_title"
                        name="job_title"
                        value="{{ old('job_title', $user->job_title) }}"
                        maxlength="255"
                    >
                </div>

                <div class="field">
                    <label for="department">Department</label>
                    <input
                        type="text"
                        id="department"
                        name="department"
                        value="{{ old('department', $user->department) }}"
                        maxlength="255"
                    >
                </div>

                <div class="field">
                    <label for="role">Access role</label>
                    <select id="role" name="role" required>
                        <option value="support" {{ old('role', $user->role) === 'support' ? 'selected' : '' }}>
                            Support
                        </option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                            Administrator
                        </option>
                    </select>
                </div>

                <hr style="border:0; border-top:1px solid #e2e8f0; margin:24px 0;">

                <h2 style="font-size:18px;">Change Password</h2>
                <p class="muted">Leave these fields blank to keep the existing password.</p>

                <div class="field">
                    <label for="password">New password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        minlength="8"
                    >
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm new password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        minlength="8"
                    >
                </div>

                <div class="actions">
                    <button class="button" type="submit">Save Profile</button>
                    <a class="button secondary" href="{{ route('users.index') }}">Cancel</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
