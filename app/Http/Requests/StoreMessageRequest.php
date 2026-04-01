<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Access control handled in controller
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|max:5000',
        ];
    }
}
