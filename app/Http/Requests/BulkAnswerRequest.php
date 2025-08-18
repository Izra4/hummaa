<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'session_id' => [
                'required',
                'integer',
                Rule::exists('tryout_results', 'hasil_id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                })
            ],
            'answers' => 'required|array',
            'answers.*' => 'nullable|integer|exists:answer_choices,pilihan_id',
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'Session ID is required.',
            'session_id.exists' => 'Invalid session or session does not belong to you.',
            'answers.required' => 'Answers are required.',
            'answers.array' => 'Answers must be an array.',
            'answers.*.exists' => 'One or more answer choices are invalid.',
        ];
    }
}