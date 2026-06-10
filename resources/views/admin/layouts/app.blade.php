<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') · Nexsol Labs</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans text-white/90 antialiased">
<div x-data="{ open: false }" class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full border-r border-white/10 bg-ink-900/70 backdrop-blur-xl transition-transform lg:translate-x-0"
           :class="open ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex h-full flex-col">
            <div class="flex items-center gap-2 px-6 py-6">
                <span class="inline-block h-2.5 w-2.5 rounded-full bg-accent-500 shadow-[0_0_12px_2px_rgba(34,211,219,0.6)]"></span>
                <span class="font-display text-lg font-semibold tracking-tight text-white">Nexsol Labs</span>
            </div>

            <nav class="flex-1 space-y-1 px-3 py-2">
                @php
                    $nav = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l9-9 9 9M4 10v10h5v-6h6v6h5V10'],
                        ['route' => 'admin.leads.index', 'label' => 'Leads', 'icon' => 'M3 8l9 6 9-6M3 6h18v12H3z'],
                        ['route' => 'admin.mailbox.index', 'label' => 'Inbox', 'icon' => 'M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z'],
                        ['route' => 'admin.projects.index', 'label' => 'Projects', 'icon' => 'M3 7h18M3 7l2-3h14l2 3M3 7v13h18V7'],
                    ];
                @endphp
                @foreach ($nav as $item)
                    @php $active = request()->routeIs(str_replace('.index', '', $item['route']) . '*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium transition
                              {{ $active ? 'bg-accent-500/15 text-white' : 'text-white/65 hover:bg-white/5 hover:text-white' }}">
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 {{ $active ? 'text-accent-400' : 'text-white/40 group-hover:text-white/70' }}"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['label'] }}
                        </span>
                        @if ($item['route'] === 'admin.leads.index')
                            @php $newCount = \App\Models\Lead::where('status', 'new')->count(); @endphp
                            @if ($newCount)
                                <span class="rounded-full bg-accent-500/20 px-2 py-0.5 text-xs font-semibold text-accent-300">{{ $newCount }}</span>
                            @endif
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-white/10 p-3">
                <div class="flex items-center gap-3 rounded-xl px-3 py-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-accent-500/20 text-sm font-semibold text-accent-300">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-white/45">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-1 px-1">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-white/55 transition hover:bg-white/5 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m12 0l-4-4m4 4l-4 4M21 3v18" />
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Mobile backdrop --}}
    <div x-show="open" x-cloak @click="open = false" class="fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

    {{-- Main --}}
    <div class="flex min-w-0 flex-1 flex-col lg:pl-64">
        <header class="sticky top-0 z-20 flex items-center gap-4 border-b border-white/10 bg-ink-950/70 px-5 py-4 backdrop-blur-xl">
            <button @click="open = !open" class="rounded-lg p-1.5 text-white/70 hover:bg-white/10 lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="min-w-0 flex-1">
                <h1 class="font-display text-xl font-semibold tracking-tight text-white">@yield('heading', View::yieldContent('title'))</h1>
                @hasSection('subheading')
                    <p class="mt-0.5 text-sm text-white/50">@yield('subheading')</p>
                @endif
            </div>
            @yield('actions')
        </header>

        @if (session('success'))
            <div class="mx-5 mt-4 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <main class="flex-1 px-5 py-6">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
