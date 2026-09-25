<?php

namespace App\Http\Requests;

use App\Models\Laporan;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $laporan = $this->route('laporan');

        return $laporan instanceof Laporan
            && $laporan->user_id === $this->user()?->id
            && $laporan->status === 'Belum Diperiksa';
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
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
        ];
    }
}