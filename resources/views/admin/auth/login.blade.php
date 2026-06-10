<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · Nexsol Labs</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full items-center justify-center px-4 py-12 font-sans">
    <div class="w-full max-w-md">
        <div class="mb-8 flex items-center justify-center gap-2">
            <span class="inline-block h-2.5 w-2.5 rounded-full bg-accent-500 shadow-[0_0_12px_2px_rgba(34,211,219,0.6)]"></span>
            <span class="font-display text-xl font-semibold tracking-tight text-white">Nexsol Labs</span>
        </div>

        <div class="card p-8">
            <p class="eyebrow mb-4">Admin</p>
            <h1 class="font-display text-2xl font-semibold text-white">Sign in</h1>
            <p class="mt-1.5 text-sm text-white/55">Manage leads and projects.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="field-input" placeholder="you@nexsollabs.com">
                </div>
                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" name="password" type="password" required
                           class="field-input" placeholder="••••••••">
                </div>
                <label class="flex items-center gap-2 text-sm text-white/60">
                    <input type="checkbox" name="remember"
                           class="h-4 w-4 rounded border-white/20 bg-ink-900 text-accent-500 focus:ring-accent-500/40">
                    Keep me signed in
                </label>
                <button type="submit" class="btn-primary w-full">Sign in</button>
            </form>
        </div>
    </div>
</body>
</html>
