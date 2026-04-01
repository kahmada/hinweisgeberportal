<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\AnonymousCredentialsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'company' => 'nullable|string|max:255',
            'violation_type' => 'nullable|string|max:255',
            'incident_date' => 'nullable|date',
            'incident_location' => 'nullable|string|max:255',
            'involved_persons' => 'nullable|string',
            'description' => 'required|string',
            'is_anonymous' => 'boolean',
        ]);

        // Check if this is an anonymous submission
        $isAnonymous = $request->boolean('is_anonymous', true);

        if ($isAnonymous) {
            // Generate anonymous credentials
            $credentials = $this->credentialsService->generate();

            $report = Report::create([
                'title' => $validated['title'],
                'company' => $validated['company'] ?? null,
                'violation_type' => $validated['violation_type'] ?? null,
                'incident_date' => $validated['incident_date'] ?? null,
                'incident_location' => $validated['incident_location'] ?? null,
                'involved_persons' => $validated['involved_persons'] ?? null,
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
                    'password' => $credentials['password'],
                    'access_token' => $credentials['token'],
                    'access_url' => url("/track/{$credentials['token']}"),
                ],
                'warning' => 'WICHTIG: Speichern Sie diese Zugangsdaten! Sie werden nicht erneut angezeigt.',
            ], 201);
        }

        // For registered users
        $report = Report::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'company' => $validated['company'] ?? null,
            'violation_type' => $validated['violation_type'] ?? null,
            'incident_date' => $validated['incident_date'] ?? null,
            'incident_location' => $validated['incident_location'] ?? null,
            'involved_persons' => $validated['involved_persons'] ?? null,
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

    /**
     * Show tracking login page with token
     */
    public function showTrackingLogin(string $token)
    {
        // Verify token exists
        $report = Report::where('anonymous_token', $token)
                       ->where('is_anonymous', true)
                       ->first();

        if (!$report) {
            abort(404, 'Ungültiger Zugangslink');
        }

        return view('track.login', compact('token'));
    }

    /**
     * Authenticate anonymous whistleblower
     */
    public function trackLogin(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $report = Report::where('anonymous_username', $validated['username'])
                       ->where('is_anonymous', true)
                       ->first();

        if (!$report || !Hash::check($validated['password'], $report->anonymous_password)) {
            return back()->withErrors([
                'credentials' => 'Ungültige Zugangsdaten. Bitte überprüfen Sie Ihren Benutzernamen und Ihr Passwort.'
            ])->withInput($request->only('username'));
        }

        // Create session for anonymous whistleblower
        session([
            'whistleblower_report_id' => $report->id,
            'whistleblower_username' => $report->anonymous_username,
        ]);

        return redirect()->route('report.view', $report->id);
    }

    /**
     * View report details (for authenticated whistleblower)
     */
    public function viewReport(int $id)
    {
        // Check if user has access to this report
        if (session('whistleblower_report_id') !== $id) {
            abort(403, 'Zugriff verweigert');
        }

        $report = Report::findOrFail($id);

        return view('track.view', compact('report'));
    }

    /**
     * Logout anonymous whistleblower
     */
    public function trackLogout()
    {
        session()->forget(['whistleblower_report_id', 'whistleblower_username']);
        return redirect('/')->with('message', 'Sie wurden erfolgreich abgemeldet.');
    }
}
