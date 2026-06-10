<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'client',
        'status',
        'production_url',
        'repo_url',
        'tech_stack',
        'hosting_provider',
        'server_info',
        'hosting_expires_at',
        'domain',
        'domain_registrar',
        'domain_expires_at',
        'monthly_cost',
        'currency',
        'start_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hosting_expires_at' => 'date',
            'domain_expires_at' => 'date',
            'start_date' => 'date',
            'monthly_cost' => 'decimal:2',
        ];
    }

    public const STATUSES = ['planning', 'active', 'maintenance', 'paused', 'completed', 'archived'];

    /**
     * Soonest of the two expiry dates, for "expiring soon" sorting/alerts.
     */
    public function nextExpiry(): ?\Illuminate\Support\Carbon
    {
        return collect([$this->hosting_expires_at, $this->domain_expires_at])
            ->filter()
            ->sort()
            ->first();
    }
}
