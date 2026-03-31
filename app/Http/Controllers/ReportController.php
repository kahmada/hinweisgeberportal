<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\AnonymousCredentialsService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private AnonymousCredentialsService $credentialsService
    ) {}

    public function index()
    {
        $reports = Report::latest()->get();
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_anonymous' => 'boolean',
        ]);

        // Check if this is an anonymous submission
        $isAnonymous = $request->boolean('is_anonymous', true); // Default to anonymous

        if ($isAnonymous) {
            // Generate anonymous credentials
            $credentials = $this->credentialsService->generate();

            $report = Report::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_anonymous' => true,
                'anonymous_username' => $credentials['username'],
                'anonymous_password' => $credentials['password_hash'],
                'anonymous_token' => $credentials['token'],
                'status' => 'eingegangen',
            ]);

            // Return credentials (ONLY SHOWN ONCE!)
            return response()->json([
                'success' => true,
                'message' => 'Hinweis erfolgreich eingereicht',
                'report_id' => $report->id,
                'credentials' => [
                    'username' => $credentials['username'],
                    'password' => $credentials['password'], // Plain text
                    'access_token' => $credentials['token'],
                    'access_url' => url("/track/{$credentials['token']}"),
                ],
                'warning' => 'WICHTIG: Speichern Sie diese Zugangsdaten! Sie werden nicht erneut angezeigt.',
            ], 201);
        }

        // For registered users (future implementation)
        $report = Report::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_anonymous' => false,
            'status' => 'eingegangen',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hinweis erfolgreich eingereicht',
            'report' => $report,
        ], 201);
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:eingegangen,in_pruefung,rueckfrage,abgeschlossen',
        ]);

        $report->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Report aktualisiert',
            'report' => $report,
        ]);
    }
}
