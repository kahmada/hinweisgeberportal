<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export reports to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = Report::with(['user', 'messages', 'attachments']);

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== '') {
            if ($request->type === 'anonymous') {
                $query->where('is_anonymous', true);
            } elseif ($request->type === 'registered') {
                $query->where('is_anonymous', false);
            }
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        $filename = 'hinweise_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Titel',
                'Gesellschaft',
                'Art des Verstoßes',
                'Datum des Vorfalls',
                'Ort',
                'Status',
                'Typ',
                'Eingereicht am',
                'Anzahl Nachrichten',
                'Anzahl Anhänge'
            ], ';');

            // CSV Data
            foreach ($reports as $report) {
                $type = $report->is_anonymous ? 'Anonym' : 'Registriert';
                
                fputcsv($file, [
                    $report->id,
                    $report->title,
                    $report->company ?? '-',
                    $report->violation_type ?? '-',
                    $report->incident_date ?? '-',
                    $report->incident_location ?? '-',
                    $this->getStatusLabel($report->status),
                    $type,
                    $report->created_at->format('d.m.Y H:i'),
                    $report->messages->count(),
                    $report->attachments->count()
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export single report details to PDF-ready HTML
     */
    public function exportReportHtml(int $id)
    {
        $report = Report::with(['messages', 'attachments', 'user', 'revealedBy'])->findOrFail($id);

        $activityLogs = \App\Models\ActivityLog::with('user')
            ->where('report_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.report-export', compact('report', 'activityLogs'));
    }

    /**
     * Get status label in German
     */
    private function getStatusLabel(string $status): string
    {
        $labels = [
            'eingegangen' => 'Eingegangen',
            'in_pruefung' => 'In Prüfung',
            'untersuchung' => 'Untersuchung',
            'rueckfrage' => 'Rückfrage',
            'abgeschlossen' => 'Abgeschlossen'
        ];

        return $labels[$status] ?? $status;
    }
}
