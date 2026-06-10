<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $soon = Carbon::today()->addDays(60);

        // Projects whose hosting or domain expires within the next 60 days.
        $expiring = Project::where('status', '!=', 'archived')
            ->where(function ($q) use ($soon) {
                $q->whereNotNull('hosting_expires_at')->where('hosting_expires_at', '<=', $soon)
                  ->orWhere(function ($q2) use ($soon) {
                      $q2->whereNotNull('domain_expires_at')->where('domain_expires_at', '<=', $soon);
                  });
            })
            ->get()
            ->sortBy(fn (Project $p) => optional($p->nextExpiry())->timestamp ?? PHP_INT_MAX)
            ->values();

        $stats = [
            'new_leads' => Lead::where('status', 'new')->count(),
            'total_leads' => Lead::count(),
            'active_projects' => Project::whereIn('status', ['active', 'maintenance'])->count(),
            'total_projects' => Project::count(),
        ];

        $recentLeads = Lead::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'expiring', 'recentLeads'));
    }
}
