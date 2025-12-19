<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isActive();
    }

    public function rules(): array
    {
        return [
            'cover_letter' => 'required|string|min:50|max:2000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}