<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuspendUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
    return [
        'reason' => 'required|string|max:500',
        'suspended_until' => 'required|date|after:today', 
    ];
    }
}