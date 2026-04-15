<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\MessageService;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private MessageService $messageService
    ) {}

    /**
     * Get all messages for a report
     */
    public function index(int $reportId)
    {
        $report = Report::findOrFail($reportId);
        $this->authorize('view', $report);

        $messages = $this->messageService->getMessages($report);

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
        $this->authorize('sendMessage', $report);

        try {
            $senderType = (auth()->check() && auth()->user()->is_admin) ? 'admin' : 'whistleblower';
            $senderId = auth()->check() ? auth()->id() : null;

            $message = $this->messageService->sendMessage(
                $report,
                $validated['message'],
                $senderType,
                $senderId
            );

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(int $reportId)
    {
        $report = Report::findOrFail($reportId);
        $this->authorize('view', $report);

        $readerType = (auth()->check() && auth()->user()->is_admin) ? 'admin' : 'whistleblower';
        $this->messageService->markMessagesAsRead($report, $readerType);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
        ]);
    }
}
