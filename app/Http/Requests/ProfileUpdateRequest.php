<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:Laki-Laki,Perempuan'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
            'password' => [
                'nullable',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean phone number
        if ($this->filled('whatsapp')) {
            $this->merge([
                'whatsapp' => $this->cleanPhoneNumber($this->whatsapp)
            ]);
        }

        // Jika password kosong, hapus dari validasi
        if (empty($this->password)) {
            $this->request->remove('password');
            $this->request->remove('password_confirmation');
        }
    }

    /**
     * Clean phone number format
     */
    protected function cleanPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except +
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);
        
        // Convert 0 prefix to +62 for Indonesian numbers
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '+62' . substr($cleaned, 1);
        }
        
        return $cleaned;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'nama depan',
            'last_name' => 'nama belakang',
            'whatsapp' => 'nomor WhatsApp',
            'birth_date' => 'tanggal lahir',
            'gender' => 'jenis kelamin',
            'photo' => 'foto profil',
            'password' => 'password',
            'password_confirmation' => 'konfirmasi password',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Nama depan wajib diisi.',
            'first_name.max' => 'Nama depan maksimal 255 karakter.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'last_name.max' => 'Nama belakang maksimal 255 karakter.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.max' => 'Nomor WhatsApp maksimal 20 karakter.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpeg, png, jpg, atau gif.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'gender.in' => 'Jenis kelamin tidak valid.',
        ];
    }
}