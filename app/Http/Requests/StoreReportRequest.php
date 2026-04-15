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
            'incident_date'     => 'nullable|date|before_or_equal:today',
            'incident_location' => 'nullable|string|max:255',
            'involved_persons'  => 'nullable|string',
            'description'       => 'required|string',
            'is_anonymous'      => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                => 'Bitte geben Sie einen Titel für den Hinweis ein.',
            'title.max'                     => 'Der Titel darf maximal 255 Zeichen enthalten.',
            'description.required'          => 'Bitte geben Sie eine Beschreibung des Vorfalls ein.',
            'incident_date.date'            => 'Bitte geben Sie ein gültiges Datum ein.',
            'incident_date.before_or_equal' => 'Das Vorfallsdatum darf nicht in der Zukunft liegen.',
        ];
    }
}

