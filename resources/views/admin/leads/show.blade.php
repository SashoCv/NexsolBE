@extends('admin.layouts.app')

@section('title', 'Lead · ' . $lead->name)
@section('heading', $lead->name)
@section('subheading', $lead->created_at->format('M j, Y · H:i'))

@section('actions')
    <a href="{{ route('admin.leads.index') }}" class="btn-ghost">← Back</a>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr]">
        {{-- Inquiry --}}
        <div class="card p-6">
            <div class="flex flex-wrap items-center gap-3">
                @include('admin.partials.status-badge', ['status' => $lead->status])
                <span class="text-xs text-white/40">from {{ $lead->source }}</span>
            </div>

            <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-white/40">Email</dt>
                    <dd class="mt-1"><a href="mailto:{{ $lead->email }}" class="text-accent-400 hover:text-accent-300">{{ $lead->email }}</a></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-white/40">Company</dt>
                    <dd class="mt-1 text-white/85">{{ $lead->company ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-white/40">Project type</dt>
                    <dd class="mt-1 text-white/85">{{ $lead->project_type ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-white/40">Budget</dt>
                    <dd class="mt-1 text-white/85">{{ $lead->budget ?: '—' }}</dd>
                </div>
            </dl>

            <div class="mt-6">
                <dt class="text-xs uppercase tracking-wide text-white/40">Message</dt>
                <dd class="mt-2 whitespace-pre-wrap rounded-xl border border-white/10 bg-ink-900/50 p-4 text-sm leading-relaxed text-white/85">{{ $lead->message }}</dd>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="mailto:{{ $lead->email }}?subject={{ rawurlencode('Re: your inquiry to Nexsol Labs') }}" class="btn-primary">Reply by email</a>
            </div>
        </div>

        {{-- Triage --}}
        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="card p-6">
                @csrf
                @method('PUT')
                <h2 class="font-display text-lg font-semibold text-white">Triage</h2>
                <p class="mt-1 text-sm text-white/50">Private — not visible to the sender.</p>

                <div class="mt-5">
                    <label for="status" class="field-label">Status</label>
                    <select id="status" name="status" class="field-input">
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected($lead->status === $s) class="bg-ink-900 capitalize">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="admin_notes" class="field-label">Notes</label>
                    <textarea id="admin_notes" name="admin_notes" rows="5" class="field-input" placeholder="Follow-up, call summary, next steps…">{{ old('admin_notes', $lead->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="btn-primary mt-5 w-full">Save</button>
            </form>

            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}"
                  x-data @submit.prevent="if (confirm('Delete this lead permanently?')) $el.submit()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger w-full">Delete lead</button>
            </form>
        </div>
    </div>
@endsection
