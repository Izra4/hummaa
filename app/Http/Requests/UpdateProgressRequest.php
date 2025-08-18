<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'progress' => 'required|integer|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'progress.required' => 'Progress is required.',
            'progress.integer' => 'Progress must be a number.',
            'progress.min' => 'Progress must be at least 0.',
            'progress.max' => 'Progress must not exceed 100.',
        ];
    }
}