<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'MAHASISWA';
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'kegiatan' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'foto.image' => 'File harus berupa gambar (jpg, png, dll).',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
