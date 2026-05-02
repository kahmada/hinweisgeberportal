<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Models\Attachment;
use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreAttachmentRequest $request, int $reportId)
    {
        $validated = $request->validated();
        $report = Report::findOrFail($reportId);
        $this->authorize('uploadAttachment', $report);

        // Additional security: validate actual file content
        foreach ($request->file('files') as $file) {
            $this->validateFileContent($file);
        }

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            // Generate secure filename
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('attachments', $filename, 'local');

            $attachment = Attachment::create([
                'report_id' => $reportId,
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $uploadedFiles[] = $attachment;
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.attachments.upload_success'),
            'attachments' => $uploadedFiles,
        ], 201);
    }

    public function download(int $id)
    {
        $attachment = Attachment::findOrFail($id);
        $report = $attachment->report;
        $this->authorize('view', $report);

        $path = storage_path('app/attachments/' . $attachment->filename);

        if (!file_exists($path)) {
            abort(404, __('messages.attachments.file_not_found'));
        }

        return response()->download($path, $attachment->original_filename, [
            'Content-Type' => $attachment->mime_type,
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Validate file content for security
     */
    private function validateFileContent($file): void
    {
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'text/plain',
        ];

        // Check actual MIME type using finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $actualMimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (!in_array($actualMimeType, $allowedMimes)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['files' => [__('messages.attachments.invalid_mime_type')]]
            );
        }

        // Check for dangerous extensions
        $filename = $file->getClientOriginalName();
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'sh', 'php', 'js', 'html', 'htm', 'phtml', 'asp', 'jsp'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $dangerousExtensions)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['files' => [__('messages.attachments.dangerous_extension')]]
            );
        }

        // Check file size (additional check)
        if ($file->getSize() > 10 * 1024 * 1024) { // 10MB
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['files' => [__('messages.attachments.file_too_large')]]
            );
        }
    }
}
