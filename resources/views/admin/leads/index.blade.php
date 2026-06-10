@extends('admin.layouts.app')

@section('title', 'Leads')
@section('heading', 'Leads')
@section('subheading', 'Inquiries from the nexsollabs.com contact form.')

@section('content')
    {{-- Status filter --}}
    <div class="mb-5 flex flex-wrap gap-2">
        <a href="{{ route('admin.leads.index') }}"
           class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition {{ !$activeStatus ? 'border-accent-400/40 bg-accent-500/15 text-white' : 'border-white/10 bg-white/5 text-white/60 hover:text-white' }}">
            All <span class="ml-1 text-white/40">{{ $leads->total() }}</span>
        </a>
        @foreach ($statuses as $s)
            <a href="{{ route('admin.leads.index', ['status' => $s]) }}"
               class="rounded-full border px-3.5 py-1.5 text-sm font-medium capitalize transition {{ $activeStatus === $s ? 'border-accent-400/40 bg-accent-500/15 text-white' : 'border-white/10 bg-white/5 text-white/60 hover:text-white' }}">
                {{ $s }} <span class="ml-1 text-white/40">{{ $counts[$s] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-white/10 text-xs uppercase tracking-wide text-white/45">
                    <tr>
                        <th class="px-5 py-3 font-medium">Received</th>
                        <th class="px-5 py-3 font-medium">Name</th>
                        <th class="px-5 py-3 font-medium">Email</th>
                        <th class="px-5 py-3 font-medium">Project</th>
                        <th class="px-5 py-3 font-medium">Budget</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse ($leads as $lead)
                        <tr class="transition hover:bg-white/[0.03]">
                            <td class="whitespace-nowrap px-5 py-3.5 text-white/50">{{ $lead->created_at->format('M j, Y') }}</td>
                            <td class="px-5 py-3.5 font-medium text-white">{{ $lead->name }}</td>
                            <td class="px-5 py-3.5 text-white/70">{{ $lead->email }}</td>
                            <td class="px-5 py-3.5 text-white/70">{{ $lead->project_type ?: '—' }}</td>
                            <td class="px-5 py-3.5 text-white/70">{{ $lead->budget ?: '—' }}</td>
                            <td class="px-5 py-3.5">@include('admin.partials.status-badge', ['status' => $lead->status])</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="text-accent-400 hover:text-accent-300">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-white/40">No leads here yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $leads->links() }}</div>
@endsection
