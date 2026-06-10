<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company',
        'project_type',
        'budget',
        'message',
        'status',
        'admin_notes',
        'source',
        'ip',
    ];

    public const STATUSES = ['new', 'contacted', 'qualified', 'won', 'lost', 'spam'];
}
