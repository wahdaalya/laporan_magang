<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKelompokRequest extends FormRequest
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
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'kampus_id' => ['nullable', 'exists:kampuses,id'],
            'dosen_id' => ['nullable', 'exists:dosens,id'],
            'pembimbing_id' => ['nullable', 'exists:pembimbings,id'],
            'departemen_id' => ['nullable', 'exists:departemens,id'],
            'nama_anggota' => ['required', 'array', 'min:1'],
            'nama_anggota.*' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_anggota.required' => 'Isi minimal 1 nama anggota.',
            'nama_anggota.min' => 'Isi minimal 1 nama anggota.',
        ];
    }
}