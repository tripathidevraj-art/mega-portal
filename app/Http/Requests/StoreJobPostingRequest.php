<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobPostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isActive();
    }

    public function rules(): array
    {
        return [
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'industry' => 'required|string|max:100',
            'job_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'remote', 'hybrid'])],
            'work_location' => 'required|string|max:255',
            'salary_range' => 'required|string|max:100',
            'app_deadline' => 'required|date|after:today',
        ];
    }

    public function messages(): array
    {
        return [
            'app_deadline.after' => 'Application deadline must be a future date.',
        ];
    }
}