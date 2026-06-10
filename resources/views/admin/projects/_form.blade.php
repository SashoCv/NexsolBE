@csrf
@if (($mode ?? 'create') === 'edit')
    @method('PUT')
@endif

@if ($errors->any())
    <div class="rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
        Please fix the errors below.
    </div>
@endif

{{-- Core --}}
<div class="card p-6">
    <h2 class="font-display text-base font-semibold text-white">Project</h2>
    <div class="mt-4 grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="title" class="field-label">Title <span class="text-accent-400">*</span></label>
            <input id="title" name="title" type="text" required value="{{ old('title', $project->title) }}" class="field-input" placeholder="PharmaVision">
            @error('title') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="client" class="field-label">Client</label>
            <input id="client" name="client" type="text" value="{{ old('client', $project->client) }}" class="field-input" placeholder="PharmaS">
        </div>
        <div>
            <label for="status" class="field-label">Status</label>
            <select id="status" name="status" class="field-input">
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" @selected(old('status', $project->status) === $s) class="bg-ink-900">{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="production_url" class="field-label">Production URL</label>
            <input id="production_url" name="production_url" type="text" value="{{ old('production_url', $project->production_url) }}" class="field-input" placeholder="https://app.example.com">
        </div>
        <div>
            <label for="repo_url" class="field-label">Repository</label>
            <input id="repo_url" name="repo_url" type="text" value="{{ old('repo_url', $project->repo_url) }}" class="field-input" placeholder="https://github.com/...">
        </div>
        <div class="sm:col-span-2">
            <label for="tech_stack" class="field-label">Tech stack</label>
            <input id="tech_stack" name="tech_stack" type="text" value="{{ old('tech_stack', $project->tech_stack) }}" class="field-input" placeholder="Laravel, React, MySQL">
        </div>
    </div>
</div>

{{-- Hosting & domain --}}
<div class="card mt-6 p-6">
    <h2 class="font-display text-base font-semibold text-white">Hosting &amp; domain</h2>
    <div class="mt-4 grid gap-5 sm:grid-cols-2">
        <div>
            <label for="hosting_provider" class="field-label">Hosting provider</label>
            <input id="hosting_provider" name="hosting_provider" type="text" value="{{ old('hosting_provider', $project->hosting_provider) }}" class="field-input" placeholder="Hetzner, Netlify, DigitalOcean…">
        </div>
        <div>
            <label for="server_info" class="field-label">Server / IP / panel</label>
            <input id="server_info" name="server_info" type="text" value="{{ old('server_info', $project->server_info) }}" class="field-input" placeholder="123.45.67.89 · cPanel">
        </div>
        <div>
            <label for="hosting_expires_at" class="field-label">Hosting expires</label>
            <input id="hosting_expires_at" name="hosting_expires_at" type="date" value="{{ old('hosting_expires_at', optional($project->hosting_expires_at)->format('Y-m-d')) }}" class="field-input">
        </div>
        <div></div>
        <div>
            <label for="domain" class="field-label">Domain</label>
            <input id="domain" name="domain" type="text" value="{{ old('domain', $project->domain) }}" class="field-input" placeholder="example.com">
        </div>
        <div>
            <label for="domain_registrar" class="field-label">Registrar</label>
            <input id="domain_registrar" name="domain_registrar" type="text" value="{{ old('domain_registrar', $project->domain_registrar) }}" class="field-input" placeholder="Namecheap, mkhost…">
        </div>
        <div>
            <label for="domain_expires_at" class="field-label">Domain expires</label>
            <input id="domain_expires_at" name="domain_expires_at" type="date" value="{{ old('domain_expires_at', optional($project->domain_expires_at)->format('Y-m-d')) }}" class="field-input">
        </div>
    </div>
</div>

{{-- Commercials & notes --}}
<div class="card mt-6 p-6">
    <h2 class="font-display text-base font-semibold text-white">Commercials &amp; notes</h2>
    <div class="mt-4 grid gap-5 sm:grid-cols-3">
        <div>
            <label for="monthly_cost" class="field-label">Monthly cost</label>
            <input id="monthly_cost" name="monthly_cost" type="number" step="0.01" min="0" value="{{ old('monthly_cost', $project->monthly_cost) }}" class="field-input" placeholder="0.00">
        </div>
        <div>
            <label for="currency" class="field-label">Currency</label>
            <input id="currency" name="currency" type="text" value="{{ old('currency', $project->currency ?: 'EUR') }}" class="field-input" placeholder="EUR">
        </div>
        <div>
            <label for="start_date" class="field-label">Start date</label>
            <input id="start_date" name="start_date" type="date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}" class="field-input">
        </div>
        <div class="sm:col-span-3">
            <label for="notes" class="field-label">Notes</label>
            <textarea id="notes" name="notes" rows="4" class="field-input" placeholder="Credentials location, renewal reminders, contacts…">{{ old('notes', $project->notes) }}</textarea>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('admin.projects.index') }}" class="btn-ghost">Cancel</a>
    <button type="submit" class="btn-primary">{{ ($mode ?? 'create') === 'edit' ? 'Save changes' : 'Create project' }}</button>
</div>
