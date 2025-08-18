<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admin users should be able to create materials
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|integer|exists:material_categories,kategori_id',
            'judul' => 'required|string|max:255',
            'isi_materi' => 'required|string',
            'file' => 'sometimes|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // 10MB max
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required' => 'Category is required.',
            'kategori_id.exists' => 'Selected category does not exist.',
            'judul.required' => 'Title is required.',
            'judul.max' => 'Title must not exceed 255 characters.',
            'isi_materi.required' => 'Content is required.',
            'file.mimes' => 'File must be a PDF, DOC, DOCX, PPT, or PPTX.',
            'file.max' => 'File size must not exceed 10MB.',
        ];
    }
}