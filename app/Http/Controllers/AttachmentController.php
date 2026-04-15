<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, int $reportId)
    {
        $request->validate([
            'files' => 'required|array|max:5',
            'files.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt',
        ]);

        $report = Report::findOrFail($reportId);
        $this->authorize('uploadAttachment', $report);

        // Additional security: validate actual file content
        foreach ($request->file('files') as $file) {
            $this->validateFileContent($file);
        }

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
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
            'message' => 'Dateien erfolgreich hochgeladen',
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
            abort(404);
        }

        return response()->download($path, $attachment->original_filename, [
            'Content-Type' => $attachment->mime_type,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

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

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['files' => ['Dateityp nicht erlaubt. Nur PDF, DOC, DOCX, JPG, PNG und TXT sind zulässig.']]
            );
        }

        // Check for executable content in filename
        $filename = $file->getClientOriginalName();
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'sh', 'php', 'js', 'html', 'htm', 'phtml'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $dangerousExtensions)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['files' => ['Dieser Dateityp ist aus Sicherheitsgründen nicht erlaubt.']]
            );
        }
    }
}
