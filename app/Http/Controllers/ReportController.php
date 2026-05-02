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
                'message' => __('messages.responses.report_submitted'),
                'report_id' => $result['report']->id,
                'credentials' => [
                    'username' => $result['credentials']['username'],
                    'password' => $result['credentials']['password'],
                    'access_token' => $result['credentials']['token'],
                    'access_url' => url("/track/{$result['credentials']['token']}"),
                ],
                'warning' => __('messages.responses.credentials_warning'),
            ], 201);
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.responses.report_submitted'),
            'report' => $result['report'],
        ], 201);
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $validated = $request->validated();
        $report = $this->reportService->updateStatus($report, $validated['status'], auth()->user());

        return response()->json([
            'success' => true,
            'message' => __('messages.responses.report_updated'),
            'report' => $report,
        ]);
    }

    public function showTrackingLogin(string $token)
    {
        $report = Report::where('anonymous_token', $token)
                       ->where('is_anonymous', true)
                       ->first();

        if (!$report) {
            abort(404, __('messages.responses.invalid_token'));
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
                'credentials' => __('messages.responses.invalid_credentials'),
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
            abort(403, __('messages.responses.access_denied'));
        }

        $report = Report::with('attachments')->findOrFail($id);
        return view('track.view', compact('report'));
    }

    public function trackLogout()
    {
        session()->forget(['whistleblower_report_id', 'whistleblower_username']);
        return redirect('/')->with('message', __('messages.responses.logout_success'));
    }

    public function revealIdentity(Request $request, Report $report)
    {
        $this->authorize('revealIdentity', $report);

        try {
            $report = $this->reportService->revealIdentity($report, auth()->user());
            $report->load('user');

            return response()->json([
                'success' => true,
                'message' => __('messages.responses.identity_revealed'),
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
