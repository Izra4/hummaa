<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'isi_soal' => 'required|string',
            'pembahasan' => 'sometimes|nullable|string',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'choices' => 'required|array|min:2|max:5',
            'choices.*.text' => 'required|string|max:500',
            'choices.*.is_correct' => 'required|boolean',
            'tryout_ids' => 'sometimes|array',
            'tryout_ids.*' => 'integer|exists:tryouts,tryout_id',
        ];
    }

    public function messages(): array
    {
        return [
            'isi_soal.required' => 'Question text is required.',
            'choices.required' => 'Answer choices are required.',
            'choices.min' => 'At least 2 answer choices are required.',
            'choices.max' => 'Maximum 5 answer choices are allowed.',
            'choices.*.text.required' => 'Choice text is required.',
            'choices.*.is_correct.required' => 'You must specify if the choice is correct.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif format.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $choices = $this->input('choices', []);
            $correctCount = collect($choices)->where('is_correct', true)->count();
            
            if ($correctCount !== 1) {
                $validator->errors()->add('choices', 'Exactly one answer choice must be marked as correct.');
            }
        });
    }
}