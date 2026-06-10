@extends('admin.layouts.app')

@section('title', 'Inbox')
@section('heading', 'Inbox')
@section('subheading', 'Incoming mail from your PrivateEmail mailbox.')

@section('content')
    @if ($error)
        <div class="card p-8 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-amber-500/10 text-amber-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg>
            </div>
            <p class="mx-auto max-w-md text-sm text-white/70">{{ $error }}</p>
        </div>
    @else
        <div class="card overflow-hidden">
            @forelse ($rows as $m)
                <a href="{{ route('admin.mailbox.show', $m['uid']) }}"
                   class="flex items-center gap-4 border-b border-white/5 px-5 py-4 transition last:border-b-0 hover:bg-white/[0.03]">
                    <span class="flex h-2 w-2 shrink-0 items-center justify-center">
                        @unless ($m['seen'])
                            <span class="h-2 w-2 rounded-full bg-accent-500 shadow-[0_0_8px_1px_rgba(34,211,219,0.6)]"></span>
                        @endunless
                    </span>
                    <div class="w-44 shrink-0 truncate {{ $m['seen'] ? 'text-white/70' : 'font-semibold text-white' }}">
                        {{ $m['from'] }}
                    </div>
                    <div class="min-w-0 flex-1 truncate {{ $m['seen'] ? 'text-white/60' : 'text-white' }}">
                        {{ $m['subject'] }}
                    </div>
                    <div class="w-28 shrink-0 text-right text-xs text-white/40">
                        {{ $m['date']?->diffForHumans(['short' => true]) ?? '' }}
                    </div>
                </a>
            @empty
                <p class="px-5 py-16 text-center text-white/40">Inbox is empty.</p>
            @endforelse
        </div>

        @if ($rows->isNotEmpty())
            <div class="mt-4 flex items-center justify-between text-sm">
                <span class="text-white/40">Page {{ $page }}</span>
                <div class="flex gap-2">
                    @if ($page > 1)
                        <a href="{{ route('admin.mailbox.index', ['page' => $page - 1]) }}" class="btn-ghost">← Newer</a>
                    @endif
                    @if ($hasNext)
                        <a href="{{ route('admin.mailbox.index', ['page' => $page + 1]) }}" class="btn-ghost">Older →</a>
                    @endif
                </div>
            </div>
        @endif
    @endif
@endsection
