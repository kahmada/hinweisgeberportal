<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::withCount(['messages as unread_messages_count' => function ($query) {
            $query->where('sender_type', 'whistleblower')->where('is_read', false);
        }])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('admin.reports', compact('reports'));
    }

    public function show(int $id)
    {
        $report = Report::with(['messages', 'attachments'])->findOrFail($id);
        
        return view('admin.report-detail', compact('report'));
    }
}
