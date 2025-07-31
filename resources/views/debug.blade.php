@if (config('app.debug'))
    <div style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 1rem; margin: 1rem 0; font-family: monospace;">
        <h4>Debug Info (2025-07-31 14:06:50 - DenuJanuari)</h4>
        <p><strong>Current Route:</strong> {{ Route::currentRouteName() }}</p>
        <p><strong>Auth Status:</strong> {{ auth()->check() ? 'Logged in' : 'Not logged in' }}</p>
        <p><strong>User ID:</strong> {{ auth()->id() }}</p>
        <p><strong>Session Data:</strong></p>
        <pre>{{ print_r(session()->all(), true) }}</pre>
    </div>
@endif
