<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'sometimes|integer|exists:material_categories,kategori_id',
            'judul' => 'sometimes|string|max:255',
            'isi_materi' => 'sometimes|string',
            'file' => 'sometimes|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ];
    }
}