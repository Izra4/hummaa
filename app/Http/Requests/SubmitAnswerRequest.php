<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitAnswerRequest extends FormRequest
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
            'question_id' => 'required|integer|exists:questions,soal_id',
            'choice_id' => 'nullable|integer|exists:answer_choices,pilihan_id',
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'Session ID is required.',
            'session_id.exists' => 'Invalid session or session does not belong to you.',
            'question_id.required' => 'Question ID is required.',
            'question_id.exists' => 'Selected question does not exist.',
            'choice_id.exists' => 'Selected answer choice does not exist.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->choice_id) {
                // Validate that choice belongs to the question
                $choiceExists = \App\Models\AnswerChoice::where('pilihan_id', $this->choice_id)
                    ->where('soal_id', $this->question_id)
                    ->exists();
                
                if (!$choiceExists) {
                    $validator->errors()->add('choice_id', 'Selected choice does not belong to the question.');
                }
            }
        });
    }
}