<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\AnonymousCredentialsService;
use App\Services\ActivityLogService;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Mail\NewReportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function __construct(
        private AnonymousCredentialsService $credentialsService
    ) {}

    public function index()
    {
        // Only admins can list all reports
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Zugriff verweigert');
        }

        $reports = Report::latest()->get();
        return response()->json($reports);
    }

    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous', true);

        if ($isAnonymous) {
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

            $this->notifyAdminNewReport($report);

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

        $this->notifyAdminNewReport($report);

        return response()->json([
            'success' => true,
            'message' => 'Hinweis erfolgreich eingereicht',
            'report' => $report,
        ], 201);
    }

    private function notifyAdminNewReport(Report $report): void
    {
        $email = config('app.notify_admin_email');
        if (!$email) return;

        try {
            Mail::to($email)->queue(new NewReportNotification($report));
        } catch (\Exception $e) {
            // Don't fail the request if mail fails
        }
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $validated = $request->validated();
        $oldStatus = $report->status;
        $report->update($validated);

        ActivityLogService::log(
            $report->id,
            'status_changed',
            $oldStatus,
            $validated['status']
        );

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

        $report = Report::with('attachments')->findOrFail($id);

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

    /**
     * Reveal identity of a registered user (Admin only)
     */
    public function revealIdentity(Request $request, Report $report)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Zugriff verweigert');
        }

        if ($report->is_anonymous) {
            return response()->json([
                'success' => false,
                'message' => 'Dieser Hinweis ist anonym. Es gibt keine Identität zu enthüllen.',
            ], 400);
        }

        if ($report->isIdentityRevealed()) {
            return response()->json([
                'success' => false,
                'message' => 'Identität wurde bereits enthüllt.',
                'revealed_at' => $report->identity_revealed_at,
                'revealed_by' => $report->revealedBy?->name,
            ], 400);
        }

        $report->update([
            'identity_revealed_at' => now(),
            'identity_revealed_by' => auth()->id(),
        ]);

        $report->load('user');

        ActivityLogService::log($report->id, 'identity_revealed');

        return response()->json([
            'success' => true,
            'message' => 'Identität wurde enthüllt',
            'user' => [
                'name' => $report->user?->name,
                'email' => $report->user?->email,
            ],
            'revealed_at' => $report->identity_revealed_at,
            'revealed_by' => auth()->user()->name,
        ]);
    }
}
