<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $projects = Project::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByRaw("FIELD(status, 'active','maintenance','planning','paused','completed','archived')")
            ->orderBy('title')
            ->get();

        return view('admin.projects.index', [
            'projects' => $projects,
            'statuses' => Project::STATUSES,
            'activeStatus' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project(['status' => 'active', 'currency' => 'EUR']),
            'statuses' => Project::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $project = Project::create($this->validated($request));

        return redirect()->route('admin.projects.index')
            ->with('success', "Project “{$project->title}” created.");
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project,
            'statuses' => Project::STATUSES,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validated($request));

        return redirect()->route('admin.projects.index')
            ->with('success', "Project “{$project->title}” updated.");
    }

    public function destroy(Project $project): RedirectResponse
    {
        $title = $project->title;
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', "Project “{$title}” deleted.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'client' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:' . implode(',', Project::STATUSES)],
            'production_url' => ['nullable', 'string', 'max:255'],
            'repo_url' => ['nullable', 'string', 'max:255'],
            'tech_stack' => ['nullable', 'string', 'max:255'],
            'hosting_provider' => ['nullable', 'string', 'max:255'],
            'server_info' => ['nullable', 'string', 'max:255'],
            'hosting_expires_at' => ['nullable', 'date'],
            'domain' => ['nullable', 'string', 'max:255'],
            'domain_registrar' => ['nullable', 'string', 'max:255'],
            'domain_expires_at' => ['nullable', 'date'],
            'monthly_cost' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'start_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
