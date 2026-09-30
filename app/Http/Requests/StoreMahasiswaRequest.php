<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'ADMIN';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nim_nip' => ['nullable', 'string', 'max:50'],
            'hp_wa' => ['nullable', 'string', 'max:30'],
            'kampus' => ['nullable', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'kelompok_id' => ['nullable', 'exists:kelompoks,id'],
            'calon_anggota_id' => ['nullable', Rule::exists('calon_anggota_kelompoks', 'id')->where(fn ($q) => $q->whereNull('user_id'))],
        ];
    }

    public function messages(): array
    {
        return [
            'foto.image' => 'File harus berupa gambar (jpg, png, dll).',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
