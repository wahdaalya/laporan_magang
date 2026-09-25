<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKelompokRequest extends FormRequest
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
            'nama_kelompok' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'kampus_id' => ['nullable', 'exists:kampuses,id'],
            'dosen_id' => ['nullable', 'exists:dosens,id'],
            'pembimbing_id' => ['nullable', 'exists:pembimbings,id'],
            'departemen_id' => ['nullable', 'exists:departemens,id'],
        ];
    }
}