<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    public function store(Request $request, int $reportId)
    {
        $request->validate([
            'files' => 'required|array|max:5',
            'files.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt',
        ]);

        $report = Report::findOrFail($reportId);

        // Check access: admin OR whistleblower with matching session
        if (auth()->check() && auth()->user()->is_admin) {
            // Admin can upload to any report
        } elseif (session('whistleblower_report_id') === $reportId) {
            // Whistleblower can upload to their own report
        } else {
            abort(403, 'Zugriff verweigert');
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

        // Check access
        if (auth()->check() && auth()->user()->is_admin) {
            // Admin can download
        } elseif (session('whistleblower_report_id') === $attachment->report_id) {
            // Whistleblower can download their own
        } else {
            abort(403);
        }

        $path = storage_path('app/attachments/' . $attachment->filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $attachment->original_filename);
    }
}
