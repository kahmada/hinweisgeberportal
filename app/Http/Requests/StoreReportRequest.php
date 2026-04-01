<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    public function rules(): array
    {
        return [
            'title'             => 'required|string|max:255',
            'company'           => 'nullable|string|max:255',
            'violation_type'    => 'nullable|string|max:255',
            'incident_date'     => 'nullable|date',
            'incident_location' => 'nullable|string|max:255',
            'involved_persons'  => 'nullable|string',
            'description'       => 'required|string',
            'is_anonymous'      => 'boolean',
        ];
    }
}
