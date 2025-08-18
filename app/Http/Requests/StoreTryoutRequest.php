<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTryoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'nama_tryout' => 'required|string|max:255',
            'jenis_tryout' => 'required|string|in:TPA,TIU,TKD',
            'deskripsi' => 'nullable|string',
            'durasi_menit' => 'required|integer|min:1|max:300',
            'is_published' => 'sometimes|boolean',
            'question_ids' => 'sometimes|array',
            'question_ids.*' => 'integer|exists:questions,soal_id',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_tryout.required' => 'Tryout name is required.',
            'nama_tryout.max' => 'Tryout name must not exceed 255 characters.',
            'jenis_tryout.required' => 'Tryout type is required.',
            'jenis_tryout.in' => 'Tryout type must be TPA, TIU, or TKD.',
            'durasi_menit.required' => 'Duration is required.',
            'durasi_menit.integer' => 'Duration must be a number.',
            'durasi_menit.min' => 'Duration must be at least 1 minute.',
            'durasi_menit.max' => 'Duration must not exceed 300 minutes.',
        ];
    }
}
