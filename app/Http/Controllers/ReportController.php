<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ReportService;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private ReportService $reportService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Report::class);

        $reports = Report::latest()->get();
        return response()->json($reports);
    }

    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous', true);

        $result = $this->reportService->createReport($validated, auth()->user());

        if ($isAnonymous) {
            return response()->json([
                'success' => true,
                'message' => 'Hinweis erfolgreich eingereicht',
                'report_id' => $result['report']->id,
                'credentials' => [
                    'username' => $result['credentials']['username'],
                    'password' => $result['credentials']['password'],
                    'access_token' => $result['credentials']['token'],
                    'access_url' => url("/track/{$result['credentials']['token']}"),
                ],
                'warning' => 'WICHTIG: Speichern Sie diese Zugangsdaten! Sie werden nicht erneut angezeigt.',
            ], 201);
        }

        return response()->json([
            'success' => true,
            'message' => 'Hinweis erfolgreich eingereicht',
            'report' => $result['report'],
        ], 201);
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $validated = $request->validated();
        $report = $this->reportService->updateStatus($report, $validated['status'], auth()->user());

        return response()->json([
            'success' => true,
            'message' => 'Report aktualisiert',
            'report' => $report,
        ]);
    }

    public function showTrackingLogin(string $token)
    {
        $report = Report::where('anonymous_token', $token)
                       ->where('is_anonymous', true)
                       ->first();

        if (!$report) {
            abort(404, 'Ungültiger Zugangslink');
        }

        return view('track.login', compact('token'));
    }

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

        session([
            'whistleblower_report_id' => $report->id,
            'whistleblower_username' => $report->anonymous_username,
        ]);

        return redirect()->route('report.view', $report->id);
    }

    public function viewReport(int $id)
    {
        if (session('whistleblower_report_id') !== $id) {
            abort(403, 'Zugriff verweigert');
        }

        $report = Report::with('attachments')->findOrFail($id);
        return view('track.view', compact('report'));
    }

    public function trackLogout()
    {
        session()->forget(['whistleblower_report_id', 'whistleblower_username']);
        return redirect('/')->with('message', 'Sie wurden erfolgreich abgemeldet.');
    }

    public function revealIdentity(Request $request, Report $report)
    {
        $this->authorize('revealIdentity', $report);

        try {
            $report = $this->reportService->revealIdentity($report, auth()->user());
            $report->load('user');

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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
