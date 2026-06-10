<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Store a new lead coming from the marketing site contact form.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'company'      => ['nullable', 'string', 'max:255'],
            'project_type' => ['nullable', 'string', 'max:255'],
            'budget'       => ['nullable', 'string', 'max:255'],
            'message'      => ['required', 'string', 'max:5000'],
        ]);

        $lead = Lead::create([
            ...$data,
            'status' => 'new',
            'source' => 'contact_form',
            'ip'     => $request->ip(),
        ]);

        return response()->json([
            'ok'      => true,
            'message' => 'Thanks — your message reached us.',
            'id'      => $lead->id,
        ], 201);
    }
}
