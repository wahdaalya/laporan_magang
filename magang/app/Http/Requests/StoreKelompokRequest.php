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
            'mahasiswa_ids' => ['required', 'array', 'min:1'],
            'mahasiswa_ids.*' => [
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'MAHASISWA')->whereNull('kelompok_id')),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'mahasiswa_ids.required' => 'Pilih minimal 1 anggota mahasiswa.',
            'mahasiswa_ids.min' => 'Pilih minimal 1 anggota mahasiswa.',
        ];
    }
}