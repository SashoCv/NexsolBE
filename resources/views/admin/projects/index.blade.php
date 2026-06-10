@extends('admin.layouts.app')

@section('title', 'Projects')
@section('heading', 'Projects')
@section('subheading', 'Hosting, domains, and status for everything you run.')

@section('actions')
    <a href="{{ route('admin.projects.create') }}" class="btn-primary">+ New project</a>
@endsection

@section('content')
    {{-- Status filter --}}
    <div class="mb-5 flex flex-wrap gap-2">
        <a href="{{ route('admin.projects.index') }}"
           class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition {{ !$activeStatus ? 'border-accent-400/40 bg-accent-500/15 text-white' : 'border-white/10 bg-white/5 text-white/60 hover:text-white' }}">All</a>
        @foreach ($statuses as $s)
            <a href="{{ route('admin.projects.index', ['status' => $s]) }}"
               class="rounded-full border px-3.5 py-1.5 text-sm font-medium capitalize transition {{ $activeStatus === $s ? 'border-accent-400/40 bg-accent-500/15 text-white' : 'border-white/10 bg-white/5 text-white/60 hover:text-white' }}">{{ $s }}</a>
        @endforeach
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($projects as $project)
            @php
                $hostDays = $project->hosting_expires_at ? (int) now()->startOfDay()->diffInDays($project->hosting_expires_at, false) : null;
                $domDays  = $project->domain_expires_at ? (int) now()->startOfDay()->diffInDays($project->domain_expires_at, false) : null;
                $expiryCls = fn ($d) => is_null($d) ? 'text-white/45'
                    : ($d <= 14 ? 'text-red-300' : ($d <= 30 ? 'text-amber-300' : 'text-white/70'));
            @endphp
            <div class="card flex flex-col p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="truncate font-display text-lg font-semibold text-white">{{ $project->title }}</h3>
                        @if ($project->client)
                            <p class="mt-0.5 truncate text-sm text-white/50">{{ $project->client }}</p>
                        @endif
                    </div>
                    @include('admin.partials.status-badge', ['status' => $project->status])
                </div>

                @if ($project->production_url || $project->tech_stack)
                    <div class="mt-3 space-y-1.5 text-sm">
                        @if ($project->production_url)
                            <a href="{{ $project->production_url }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 text-accent-400 hover:text-accent-300">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5m0-5L10 14M9 5H5v14h14v-4"/></svg>
                                {{ \Illuminate\Support\Str::of($project->production_url)->replace(['https://','http://'], '')->rtrim('/') }}
                            </a>
                        @endif
                        @if ($project->tech_stack)
                            <p class="text-white/50">{{ $project->tech_stack }}</p>
                        @endif
                    </div>
                @endif

                <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3 border-t border-white/5 pt-4 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-white/35">Hosting</dt>
                        <dd class="mt-0.5 text-white/75">{{ $project->hosting_provider ?: '—' }}</dd>
                        @if ($project->hosting_expires_at)
                            <dd class="mt-0.5 text-xs {{ $expiryCls($hostDays) }}">exp. {{ $project->hosting_expires_at->format('M j, Y') }}</dd>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-white/35">Domain</dt>
                        <dd class="mt-0.5 truncate text-white/75">{{ $project->domain ?: '—' }}</dd>
                        @if ($project->domain_expires_at)
                            <dd class="mt-0.5 text-xs {{ $expiryCls($domDays) }}">exp. {{ $project->domain_expires_at->format('M j, Y') }}</dd>
                        @endif
                    </div>
                    @if ($project->domain_registrar)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/35">Registrar</dt>
                            <dd class="mt-0.5 text-white/75">{{ $project->domain_registrar }}</dd>
                        </div>
                    @endif
                    @if (!is_null($project->monthly_cost))
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/35">Monthly</dt>
                            <dd class="mt-0.5 text-white/75">{{ rtrim(rtrim(number_format($project->monthly_cost, 2), '0'), '.') }} {{ $project->currency }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-auto flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('admin.projects.edit', $project) }}" class="text-sm font-medium text-white/70 hover:text-white">Edit</a>
                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                          x-data @submit.prevent="if (confirm('Delete “{{ $project->title }}”?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-medium text-red-400/80 hover:text-red-300">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card col-span-full p-12 text-center">
                <p class="text-white/50">No projects yet.</p>
                <a href="{{ route('admin.projects.create') }}" class="btn-primary mt-4">+ Add your first project</a>
            </div>
        @endforelse
    </div>
@endsection
