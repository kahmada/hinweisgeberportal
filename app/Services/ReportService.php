<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use App\Mail\NewReportNotification;
use Illuminate\Support\Facades\Mail;

class ReportService
{
    public function __construct(
        private AnonymousCredentialsService $credentialsService,
        private ActivityLogService $activityLogService
    ) {}

    public function createReport(array $data, ?User $user = null): array
    {
        $isAnonymous = $data['is_anonymous'] ?? true;

        if ($isAnonymous) {
            $credentials = $this->credentialsService->generate();

            $report = Report::create([
                'title' => $data['title'],
                'company' => $data['company'] ?? null,
                'violation_type' => $data['violation_type'] ?? null,
                'incident_date' => $data['incident_date'] ?? null,
                'incident_location' => $data['incident_location'] ?? null,
                'involved_persons' => $data['involved_persons'] ?? null,
                'description' => $data['description'],
                'is_anonymous' => true,
                'anonymous_username' => $credentials['username'],
                'anonymous_password' => $credentials['password_hash'],
                'anonymous_token' => $credentials['token'],
                'status' => 'eingegangen',
            ]);

            $this->activityLogService->log($report->id, 'report_created', null, 'anonymous');
            $this->notifyAdmin($report);

            return [
                'report' => $report,
                'credentials' => $credentials,
            ];
        }

        $report = Report::create([
            'user_id' => $user?->id,
            'title' => $data['title'],
            'company' => $data['company'] ?? null,
            'violation_type' => $data['violation_type'] ?? null,
            'incident_date' => $data['incident_date'] ?? null,
            'incident_location' => $data['incident_location'] ?? null,
            'involved_persons' => $data['involved_persons'] ?? null,
            'description' => $data['description'],
            'is_anonymous' => false,
            'status' => 'eingegangen',
        ]);

        $this->activityLogService->log($report->id, 'report_created', null, 'user');
        $this->notifyAdmin($report);

        return [
            'report' => $report,
            'credentials' => null,
        ];
    }

    public function updateStatus(Report $report, string $status, ?User $admin = null): Report
    {
        $oldStatus = $report->status;
        $report->update(['status' => $status]);

        $this->activityLogService->log(
            $report->id,
            'status_changed',
            $oldStatus,
            'admin',
            $status,
            $admin?->id
        );

        return $report;
    }

    public function revealIdentity(Report $report, User $admin): Report
    {
        if ($report->isIdentityRevealed()) {
            throw new \Exception('Identität wurde bereits enthüllt');
        }

        if ($report->is_anonymous || !$report->user_id) {
            throw new \Exception('Keine Identität zum Enthüllen vorhanden');
        }

        $report->update([
            'identity_revealed_at' => now(),
            'identity_revealed_by' => $admin->id,
        ]);

        $this->activityLogService->log(
            $report->id,
            'identity_revealed',
            null,
            'admin',
            null,
            $admin->id
        );

        return $report;
    }

    public function canUserAccessReport(Report $report, ?User $user = null, ?int $sessionReportId = null): bool
    {
        if ($user && $user->is_admin) {
            return true;
        }

        if ($user && $report->user_id === $user->id) {
            return true;
        }

        if ($sessionReportId === $report->id) {
            return true;
        }

        return false;
    }

    private function notifyAdmin(Report $report): void
    {
        $email = config('app.notify_admin_email');
        if ($email) {
            try {
                Mail::to($email)->queue(new NewReportNotification($report));
            } catch (\Exception $e) {
                // Don't fail the request if mail fails
            }
        }
    }
}
