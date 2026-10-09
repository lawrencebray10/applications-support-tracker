
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Activity | ASAT</title>

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
            max-width: 650px;
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
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 27px;
        }

        .subtitle {
            margin-bottom: 28px;
            color: #64748b;
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

        .hint {
            margin-top: 6px;
            color: #64748b;
            font-size: 12px;
            line-height: 1.5;
        }

        input, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            font-size: 14px;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        input:focus, textarea:focus {
            outline: 2px solid #99f6e4;
            border-color: #0f766e;
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

        .button-secondary:hover {
            background: #cbd5e1;
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
            <h1>Add Support Activity</h1>

            <p class="subtitle">
                Define a recurring task that the support team needs to monitor.
            </p>

            @if ($errors->any())
                <div class="error-summary" role="alert">
                    Please correct the errors below before submitting.
                </div>
            @endif

            <form method="POST" action="{{ route('activities.store') }}">
                @csrf

                <div class="form-group">
                    <label for="title">Activity title *</label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        maxlength="255"
                        required
                        autofocus
                    >

                    <p class="hint">
                        Example: Daily SMS Count vs Logs
                    </p>

                    @error('title')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>

                    <textarea
                        id="description"
                        name="description"
                        maxlength="5000"
                    >{{ old('description') }}</textarea>

                    <p class="hint">
                        Optional: explain what the staff member should check
                        or record when performing this task.
                    </p>

                    @error('description')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="actions">
                    <button class="button" type="submit">
                        Save Activity
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
