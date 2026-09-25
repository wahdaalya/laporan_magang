<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddAnggotaKelompokRequest extends FormRequest
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
            'mahasiswa_id' => [
                'required',
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
            'mahasiswa_id.exists' => 'Mahasiswa yang dipilih tidak valid atau sudah punya kelompok lain.',
        ];
    }
}