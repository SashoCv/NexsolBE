@extends('admin.layouts.app')

@section('title', $error ? 'Inbox' : $subject)
@section('heading', 'Message')

@section('actions')
    <a href="{{ route('admin.mailbox.index') }}" class="btn-ghost">← Inbox</a>
@endsection

@section('content')
    @if ($error)
        <div class="card p-8 text-center text-sm text-white/70">{{ $error }}</div>
    @else
        <div class="card p-6">
            <h2 class="font-display text-xl font-semibold text-white">{{ $subject }}</h2>
            <div class="mt-3 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm text-white/55">
                <span><span class="text-white/40">From:</span> <span class="text-white/80">{{ $from }}</span></span>
                @if ($to)
                    <span><span class="text-white/40">To:</span> {{ $to }}</span>
                @endif
                @if ($date)
                    <span>{{ $date->format('M j, Y · H:i') }}</span>
                @endif
            </div>

            {{-- Attachments --}}
            @if ($attachments->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2 border-t border-white/5 pt-4">
                    @foreach ($attachments as $a)
                        <a href="{{ route('admin.mailbox.attachment', [$uid, $a['index']]) }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white/80 transition hover:border-accent-400/40 hover:bg-accent-500/10">
                            <svg class="h-4 w-4 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.4 11.6 12 21a5 5 0 0 1-7-7l9.2-9.2a3.3 3.3 0 0 1 4.7 4.7l-9.1 9.1a1.7 1.7 0 0 1-2.4-2.4l8.5-8.4"/></svg>
                            <span class="max-w-[14rem] truncate">{{ $a['name'] }}</span>
                            <span class="text-xs text-white/35">{{ $a['size'] ? \Illuminate\Support\Number::fileSize($a['size'], precision: 0) : '' }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Body in a sandboxed iframe: no scripts, remote images/trackers blocked via CSP --}}
        @php
            $bodyHtml = $html ?: nl2br(e($text ?: '(empty message)'));
            $frame = '<!doctype html><html><head><meta charset="utf-8">'
                . '<meta http-equiv="Content-Security-Policy" content="default-src \'none\'; img-src data:; style-src \'unsafe-inline\' data:; font-src data:;">'
                . '<base target="_blank">'
                . '<style>html,body{margin:0}body{font-family:Inter,system-ui,sans-serif;color:#0b1220;background:#fff;padding:20px;line-height:1.5;word-break:break-word}a{color:#0e7490}img{max-width:100%;height:auto}</style>'
                . '</head><body>' . $bodyHtml . '</body></html>';
        @endphp
        <div class="card mt-4 overflow-hidden p-0">
            <iframe sandbox srcdoc="{{ $frame }}" class="h-[70vh] w-full rounded-2xl bg-white" title="Message body"></iframe>
        </div>
        <p class="mt-2 text-xs text-white/35">Remote images and scripts are blocked for your safety.</p>
    @endif
@endsection
