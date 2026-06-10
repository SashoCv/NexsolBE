@php
    $map = [
        // leads
        'new' => 'text-sky-300 border-sky-400/30 bg-sky-500/10',
        'contacted' => 'text-amber-300 border-amber-400/30 bg-amber-500/10',
        'qualified' => 'text-accent-300 border-accent-400/30 bg-accent-500/10',
        'won' => 'text-emerald-300 border-emerald-400/30 bg-emerald-500/10',
        'lost' => 'text-red-300 border-red-400/30 bg-red-500/10',
        'spam' => 'text-white/40 border-white/15 bg-white/5',
        // projects
        'active' => 'text-emerald-300 border-emerald-400/30 bg-emerald-500/10',
        'maintenance' => 'text-accent-300 border-accent-400/30 bg-accent-500/10',
        'planning' => 'text-sky-300 border-sky-400/30 bg-sky-500/10',
        'paused' => 'text-amber-300 border-amber-400/30 bg-amber-500/10',
        'completed' => 'text-violet-300 border-violet-400/30 bg-violet-500/10',
        'archived' => 'text-white/40 border-white/15 bg-white/5',
    ];
    $cls = $map[$status] ?? 'text-white/60 border-white/15 bg-white/5';
@endphp
<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium capitalize {{ $cls }}">
    {{ $status }}
</span>
