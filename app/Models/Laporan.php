<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'kegiatan',
        'keterangan',
        'foto',
        'status',
        'catatan_pembimbing',
        'diperiksa_oleh',
        'status_mengetahui',
        'mengetahui_oleh',
        'mengetahui_at',
        'status_setuju',
        'disetujui_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'status_mengetahui' => 'boolean',
            'status_setuju' => 'boolean',
            'mengetahui_at' => 'datetime',
            'disetujui_at' => 'datetime',
        ];
    }

    /**
     * Laporan yang sudah di-ACC pembimbing (status Disetujui) terkunci dan tidak boleh diubah/dihapus.
     */
    public function isTerkunci(): bool
    {
        return $this->status === 'Disetujui';
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? route('media.laporan-photo', $this) : null;
    }

    /**
     * Foto dokumentasi dalam bentuk data URI (JPEG kecil) supaya bisa ditampilkan di PDF.
     * DomPDF tidak bisa mengakses route media yang butuh login / disk private.
     */
    public function getFotoDataUriAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        $disk = Storage::disk('local');
        if (! $disk->exists($this->foto)) {
            return null;
        }

        $mimeType = $disk->mimeType($this->foto);
        if (! in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return null;
        }

        $contents = $disk->get($this->foto);
        $dimensions = @getimagesizefromstring($contents);
        if (! $dimensions || $dimensions[0] * $dimensions[1] > 20000000) {
            return null;
        }

        $source = @imagecreatefromstring($contents);
        if (! $source) {
            return null;
        }

        $targetWidth = min(450, imagesx($source));
        $targetHeight = max(1, (int) round(imagesy($source) * ($targetWidth / imagesx($source))));
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
        imagefill($thumbnail, 0, 0, imagecolorallocate($thumbnail, 255, 255, 255));
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, imagesx($source), imagesy($source));

        ob_start();
        imagejpeg($thumbnail, null, 72);
        $jpeg = ob_get_clean();
        imagedestroy($source);
        imagedestroy($thumbnail);

        return is_string($jpeg) && $jpeg !== ''
            ? 'data:image/jpeg;base64,'.base64_encode($jpeg)
            : null;
    }
}
