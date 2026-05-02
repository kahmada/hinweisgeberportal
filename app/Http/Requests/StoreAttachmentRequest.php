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
            'files.required'    => __('messages.attachments.files_required'),
            'files.max'         => __('messages.attachments.files_max'),
            'files.*.required'  => __('messages.attachments.file_invalid'),
            'files.*.file'      => __('messages.attachments.file_not_valid'),
            'files.*.max'       => __('messages.attachments.file_size_exceeded'),
            'files.*.mimes'     => __('messages.attachments.file_type_not_allowed'),
            'files.*.mimetypes' => __('messages.attachments.invalid_mime'),
        ];
    }
}