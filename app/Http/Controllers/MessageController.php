<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Report;
use App\Services\ActivityLogService;
use App\Http\Requests\StoreMessageRequest;
use App\Mail\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * Get all messages for a report
     */
    public function index(int $reportId)
    {
        $report = Report::findOrFail($reportId);

        // Check access
        if (auth()->check()) {
            // Admin access OR registered user accessing their own report
            if (!auth()->user()->is_admin && $report->user_id !== auth()->id()) {
                abort(403);
            }
        } else {
            // Anonymous whistleblower access
            if (session('whistleblower_report_id') !== $reportId) {
                abort(403);
            }
        }

        $messages = Message::where('report_id', $reportId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Send a message (Admin or Whistleblower)
     */
    public function store(StoreMessageRequest $request, int $reportId)
    {
        $validated = $request->validated();

        $report = Report::findOrFail($reportId);

        // Determine sender type
        if (auth()->check() && auth()->user()->is_admin) {
            // Admin sending message
            $message = Message::create([
                'report_id' => $reportId,
                'sender_type' => 'admin',
                'sender_id' => auth()->id(),
                'message' => $validated['message'],
                'is_read' => false,
            ]);

            if ($report->status === 'eingegangen' || $report->status === 'in_pruefung') {
                $report->update(['status' => 'rueckfrage']);
            }

            ActivityLogService::log($reportId, 'message_sent', null, 'admin');
        } elseif (session('whistleblower_report_id') === $reportId || (auth()->check() && $report->user_id === auth()->id())) {
            // Whistleblower sending message (anonymous OR registered user)
            $message = Message::create([
                'report_id' => $reportId,
                'sender_type' => 'whistleblower',
                'sender_id' => auth()->check() ? auth()->id() : null,
                'message' => $validated['message'],
                'is_read' => false,
            ]);

            // Notify admin
            $email = config('app.notify_admin_email');
            if ($email) {
                try {
                    $preview = mb_substr($validated['message'], 0, 50);
                    Mail::to($email)->queue(new NewMessageNotification($report, $preview));
                } catch (\Exception $e) {
                    // Don't fail the request if mail fails
                }
            }
        } else {
            abort(403, 'Zugriff verweigert');
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ], 201);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(int $reportId)
    {
        $report = Report::findOrFail($reportId);

        if (auth()->check() && auth()->user()->is_admin) {
            // Admin marks whistleblower messages as read
            Message::where('report_id', $reportId)
                ->where('sender_type', 'whistleblower')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } elseif (session('whistleblower_report_id') === $reportId || (auth()->check() && $report->user_id === auth()->id())) {
            // Whistleblower (anonymous OR registered) marks admin messages as read
            Message::where('report_id', $reportId)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            abort(403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
        ]);
    }
}
