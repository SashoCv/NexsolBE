@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Overview of leads and project health.')

@section('content')
    {{-- Stat cards --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php
            $cards = [
                ['label' => 'New leads', 'value' => $stats['new_leads'], 'href' => route('admin.leads.index', ['status' => 'new']), 'accent' => true],
                ['label' => 'Total leads', 'value' => $stats['total_leads'], 'href' => route('admin.leads.index')],
                ['label' => 'Active projects', 'value' => $stats['active_projects'], 'href' => route('admin.projects.index')],
                ['label' => 'All projects', 'value' => $stats['total_projects'], 'href' => route('admin.projects.index')],
            ];
        @endphp
        @foreach ($cards as $c)
            <a href="{{ $c['href'] }}" class="card p-5 transition hover:border-white/20 hover:bg-white/[0.05]">
                <p class="text-sm text-white/55">{{ $c['label'] }}</p>
                <p class="mt-2 font-display text-3xl font-semibold {{ ($c['accent'] ?? false) && $c['value'] > 0 ? 'text-accent-400' : 'text-white' }}">
                    {{ $c['value'] }}
                </p>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        {{-- Expiring soon --}}
        <div class="card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-display text-lg font-semibold text-white">Expiring soon</h2>
                <span class="text-xs text-white/45">next 60 days</span>
            </div>

            @forelse ($expiring as $project)
                @php $next = $project->nextExpiry(); $days = $next ? (int) now()->startOfDay()->diffInDays($next, false) : null; @endphp
                <div class="flex items-center justify-between gap-4 border-t border-white/5 py-3 first:border-t-0">
                    <div class="min-w-0">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="truncate font-medium text-white hover:text-accent-300">{{ $project->title }}</a>
                        <p class="mt-0.5 truncate text-xs text-white/45">
                            @if ($project->domain_expires_at)
                                Domain {{ $project->domain_expires_at->format('M j, Y') }}
                            @endif
                            @if ($project->hosting_expires_at)
                                · Hosting {{ $project->hosting_expires_at->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                    @if (!is_null($days))
                        <span @class([
                            'shrink-0 rounded-full border px-2.5 py-0.5 text-xs font-semibold',
                            'text-red-300 border-red-400/30 bg-red-500/10' => $days <= 14,
                            'text-amber-300 border-amber-400/30 bg-amber-500/10' => $days > 14 && $days <= 30,
                            'text-white/60 border-white/15 bg-white/5' => $days > 30,
                        ])>
                            {{ $days < 0 ? abs($days) . 'd overdue' : 'in ' . $days . 'd' }}
                        </span>
                    @endif
                </div>
            @empty
                <p class="py-6 text-center text-sm text-white/40">Nothing expiring soon. 🎉</p>
            @endforelse
        </div>

        {{-- Recent leads --}}
        <div class="card p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-display text-lg font-semibold text-white">Recent leads</h2>
                <a href="{{ route('admin.leads.index') }}" class="text-xs text-accent-400 hover:text-accent-300">View all</a>
            </div>

            @forelse ($recentLeads as $lead)
                <a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center justify-between gap-4 border-t border-white/5 py-3 first:border-t-0 hover:opacity-90">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-white">{{ $lead->name }}</p>
                        <p class="truncate text-xs text-white/45">{{ $lead->email }} · {{ $lead->created_at->diffForHumans() }}</p>
                    </div>
                    @include('admin.partials.status-badge', ['status' => $lead->status])
                </a>
            @empty
                <p class="py-6 text-center text-sm text-white/40">No leads yet.</p>
            @endforelse
        </div>
    </div>
@endsection
