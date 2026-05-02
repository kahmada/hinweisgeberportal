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
            'title'             => 'required|string|min:5|max:200',
            'company'           => 'nullable|string|max:255',
            'violation_type'    => 'nullable|string|max:255',
            'incident_date'     => 'nullable|date|before_or_equal:today',
            'incident_location' => 'nullable|string|max:255',
            'involved_persons'  => 'nullable|string|max:2000',
            'description'       => 'required|string|min:20|max:10000',
            'is_anonymous'      => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                => __('messages.validation.title_required'),
            'title.min'                     => __('messages.validation.title_min'),
            'title.max'                     => __('messages.validation.title_max'),
            'description.required'          => __('messages.validation.description_required'),
            'description.min'               => __('messages.validation.description_min'),
            'description.max'               => __('messages.validation.description_max'),
            'incident_date.date'            => __('messages.validation.incident_date_date'),
            'incident_date.before_or_equal' => __('messages.validation.incident_date_future'),
            'involved_persons.max'          => __('messages.validation.involved_persons_max'),
        ];
    }
}

