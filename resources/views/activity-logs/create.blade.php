
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Activity Update | ASAT</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
        }

        .container {
            width: 100%;
            max-width: 680px;
            margin: 30px auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0f766e;
            text-decoration: none;
            font-size: 14px;
        }

        .card {
            padding: 32px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: white;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.05);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 27px;
        }

        .subtitle {
            margin: 0 0 24px;
            color: #64748b;
            line-height: 1.5;
        }

        .activity-name {
            margin-bottom: 26px;
            padding: 16px;
            border-left: 4px solid #0f766e;
            border-radius: 6px;
            background: #f0fdfa;
        }

        .activity-name strong {
            display: block;
            margin-bottom: 6px;
        }

        .activity-name p {
            margin: 0;
            color: #475569;
            font-size: 14px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            font: inherit;
            font-size: 14px;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #99f6e4;
            border-color: #0f766e;
        }

        .hint {
            margin-top: 6px;
            color: #64748b;
            font-size: 12px;
            line-height: 1.5;
        }

        .error {
            margin-top: 6px;
            color: #b91c1c;
            font-size: 13px;
        }

        .error-summary {
            margin-bottom: 20px;
            padding: 14px;
            border-radius: 8px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 14px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .button {
            display: inline-block;
            padding: 12px 16px;
            border: none;
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
            background: #e2e8f0;
            color: #334155;
        }

        @media (max-width: 480px) {
            .card {
                padding: 22px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <a class="back-link" href="{{ route('activities.index') }}">
            &larr; Back to activities
        </a>

        <section class="card">
            <h1>Record Activity Update</h1>

            <p class="subtitle">
                Record the current progress and leave useful notes for the team.
            </p>

            <div class="activity-name">
                <strong>{{ $activity->title }}</strong>
                <p>
                    {{ $activity->description ?: 'No additional description provided.' }}
                </p>
            </div>

            @if ($errors->any())
                <div class="error-summary" role="alert">
                    Please correct the errors below before submitting.
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('activity-logs.store', $activity) }}"
            >
                @csrf

                <div class="form-group">
                    <label for="activity_date">Activity date *</label>

                    <input
                        id="activity_date"
                        type="date"
                        name="activity_date"
                        value="{{ old('activity_date', now()->toDateString()) }}"
                        required
                    >

                    @error('activity_date')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>

                    <select id="status" name="status" required>
                        <option value="pending"
                            {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="in_progress"
                            {{ old('status') === 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>

                        <option value="done"
                            {{ old('status') === 'done' ? 'selected' : '' }}>
                            Done
                        </option>
                    </select>

                    @error('status')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="remark">Remarks</label>

                    <textarea
                        id="remark"
                        name="remark"
                        maxlength="5000"
                        placeholder="Describe what was checked, any issues found, and what the next shift needs to know..."
                    >{{ old('remark') }}</textarea>

                    <p class="hint">
                        Include relevant findings, unresolved issues, or follow-up actions.
                    </p>

                    @error('remark')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="actions">
                    <button class="button" type="submit">
                        Save Update
                    </button>

                    <a
                        class="button button-secondary"
                        href="{{ route('activities.index') }}"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
