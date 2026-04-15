<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use App\Mail\NewMessageNotification;
use Illuminate\Support\Facades\Mail;

class MessageService
{
    public function __construct(
        private ActivityLogService $activityLogService
    ) {}

    public function sendMessage(Report $report, string $messageText, string $senderType, ?int $senderId = null): Message
    {
        if ($report->status === 'abgeschlossen') {
            throw new \Exception('Dieser Hinweis ist abgeschlossen. Neue Nachrichten können nicht mehr gesendet werden.');
        }

        $message = Message::create([
            'report_id' => $report->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $messageText,
            'is_read' => false,
        ]);

        if ($senderType === 'admin') {
            if (in_array($report->status, ['eingegangen', 'in_pruefung'])) {
                $report->update(['status' => 'rueckfrage']);
            }
            $this->activityLogService->log($report->id, 'message_sent', null, 'admin');
        } else {
            $this->notifyAdmin($report, $messageText);
        }

        return $message;
    }

    public function markMessagesAsRead(Report $report, string $readerType): int
    {
        $query = Message::where('report_id', $report->id)
            ->where('is_read', false);

        if ($readerType === 'admin') {
            $query->where('sender_type', 'whistleblower');
        } else {
            $query->where('sender_type', 'admin');
        }

        return $query->update(['is_read' => true]);
    }

    public function getMessages(Report $report)
    {
        return Message::where('report_id', $report->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    private function notifyAdmin(Report $report, string $messageText): void
    {
        $email = config('app.notify_admin_email');
        if ($email) {
            try {
                $preview = mb_substr($messageText, 0, 50);
                Mail::to($email)->queue(new NewMessageNotification($report, $preview));
            } catch (\Exception $e) {
                // Don't fail the request if mail fails
            }
        }
    }
}
