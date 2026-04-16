<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files' => 'required|array|max:5',
            'files.*' => [
                'required',
                'file',
                'max:10240', // 10MB in KB
                'mimes:pdf,doc,docx,jpg,jpeg,png,txt',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,text/plain'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Bitte wählen Sie mindestens eine Datei aus.',
            'files.max' => 'Sie können maximal 5 Dateien gleichzeitig hochladen.',
            'files.*.required' => 'Eine der ausgewählten Dateien ist ungültig.',
            'files.*.file' => 'Eine der ausgewählten Dateien ist keine gültige Datei.',
            'files.*.max' => 'Eine oder mehrere Dateien sind größer als 10 MB.',
            'files.*.mimes' => 'Nur folgende Dateitypen sind erlaubt: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT.',
            'files.*.mimetypes' => 'Eine oder mehrere Dateien haben einen ungültigen MIME-Type.',
        ];
    }
}