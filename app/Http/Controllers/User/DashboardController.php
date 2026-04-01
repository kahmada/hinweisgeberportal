<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $reports = Report::where('user_id', auth()->id())
            ->withCount(['messages as unread_messages_count' => function ($query) {
                $query->where('sender_type', 'admin')->where('is_read', false);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.dashboard', compact('reports'));
    }

    public function show(int $id)
    {
        $report = Report::where('user_id', auth()->id())
            ->with(['messages', 'attachments'])
            ->findOrFail($id);

        return view('user.report-detail', compact('report'));
    }
}
