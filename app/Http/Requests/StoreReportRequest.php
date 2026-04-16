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
            'title.required'                => 'Bitte geben Sie einen Titel für den Hinweis ein.',
            'title.min'                     => 'Der Titel muss mindestens 5 Zeichen enthalten.',
            'title.max'                     => 'Der Titel darf maximal 200 Zeichen enthalten.',
            'description.required'          => 'Bitte geben Sie eine Beschreibung des Vorfalls ein.',
            'description.min'               => 'Die Beschreibung muss mindestens 20 Zeichen enthalten.',
            'description.max'               => 'Die Beschreibung darf maximal 10.000 Zeichen enthalten.',
            'incident_date.date'            => 'Bitte geben Sie ein gültiges Datum ein.',
            'incident_date.before_or_equal' => 'Das Vorfallsdatum darf nicht in der Zukunft liegen.',
            'involved_persons.max'          => 'Die Beschreibung beteiligter Personen darf maximal 2.000 Zeichen enthalten.',
        ];
    }
}

