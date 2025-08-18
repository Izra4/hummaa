<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartTryoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tryout_id' => 'required|integer|exists:tryouts,tryout_id',
            'mode' => 'sometimes|string|in:tryout,belajar',
        ];
    }

    public function messages(): array
    {
        return [
            'tryout_id.required' => 'Tryout ID is required.',
            'tryout_id.exists' => 'Selected tryout does not exist.',
            'mode.in' => 'Mode must be either tryout or belajar.',
        ];
    }
}