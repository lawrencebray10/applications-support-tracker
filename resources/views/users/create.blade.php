
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Staff Account | Applications Support Tracker</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #172033;
        }

        .container {
            max-width: 760px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .header p {
            margin: 0;
            color: #64748b;
            line-height: 1.5;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }

        input:focus, select:focus {
            outline: 2px solid #bfdbfe;
            border-color: #2563eb;
        }

        .hint {
            color: #64748b;
            font-size: 12px;
        }

        .error-box {
            margin-bottom: 20px;
            padding: 14px;
            border-radius: 8px;
            background: #fef2f2;
            color: #b91c1c;
        }

        .error-box ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            border: 0;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .secondary {
            background: #e2e8f0;
            color: #334155;
        }

        .primary {
            background: #1d4ed8;
            color: #fff;
        }

        @media (max-width: 560px) {
            .container {
                padding: 22px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <h1>Create Staff Account</h1>
            <p>Create a login account and assign the staff member's details and role.</p>
        </header>

        @if ($errors->any())
            <div class="error-box">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="name">Full Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        maxlength="255"
                        autocomplete="name"
                    >
                </div>

                <div class="field">
                    <label for="email">Email Address *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        maxlength="255"
                        autocomplete="email"
                    >
                </div>

                <div class="field">
                    <label for="employee_id">Employee ID</label>
                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id') }}"
                        maxlength="100"
                    >
                </div>

                <div class="field">
                    <label for="job_title">Job Title</label>
                    <input
                        type="text"
                        id="job_title"
                        name="job_title"
                        value="{{ old('job_title') }}"
                        maxlength="255"
                    >
                </div>

                <div class="field">
                    <label for="department">Department</label>
                    <input
                        type="text"
                        id="department"
                        name="department"
                        value="{{ old('department') }}"
                        maxlength="255"
                    >
                </div>

                <div class="field">
                    <label for="role">Account Role *</label>
                    <select id="role" name="role" required>
                        <option value="support" @selected(old('role', 'support') === 'support')>
                            Support Staff
                        </option>
                        <option value="admin" @selected(old('role') === 'admin')>
                            Administrator
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label for="password">Password *</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >
                    <span class="hint">Use at least 8 characters.</span>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('users.index') }}" class="button secondary">
                    Cancel
                </a>

                <button type="submit" class="button primary">
                    Create Account
                </button>
            </div>
        </form>
    </main>
</body>
</html>
