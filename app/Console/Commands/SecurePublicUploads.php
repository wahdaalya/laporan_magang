<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('app:secure-public-uploads')]
#[Description('Move existing user uploads from public storage to private storage.')]
class SecurePublicUploads extends Command
{
    public function handle(): int
    {
        $privateDisk = Storage::disk('local');
        $sourceDisks = [Storage::disk('public')];

        if (is_dir(public_path('storage'))) {
            $sourceDisks[] = Storage::build([
                'driver' => 'local',
                'root' => public_path('storage'),
                'throw' => false,
            ]);
        }
        $directories = ['foto-profil', 'laporan-foto', 'laporan-akhir', 'penilaian'];
        $movedFiles = 0;
        $conflicts = 0;

        foreach ($sourceDisks as $sourceDisk) {
            foreach ($directories as $directory) {
                foreach ($sourceDisk->allFiles($directory) as $path) {
                    if ($privateDisk->exists($path)) {
                        $sourceHash = hash_file('sha256', $sourceDisk->path($path));
                        $privateHash = hash_file('sha256', $privateDisk->path($path));

                        if ($sourceHash === false || $sourceHash !== $privateHash) {
                            $this->error('Conflict file saat memindahkan unggahan; tidak ada berkas yang ditimpa.');
                            $conflicts++;

                            continue;
                        }

                        $sourceDisk->delete($path);

                        continue;
                    }

                    if (! $sourceDisk->moveToDisk('local', $path)) {
                        $this->error('Gagal memindahkan satu berkas unggahan.');
                        $conflicts++;

                        continue;
                    }

                    $movedFiles++;
                }
            }
        }

        $this->info("Berkas dipindahkan: {$movedFiles}. Konflik: {$conflicts}.");

        return $conflicts === 0 ? self::SUCCESS : self::FAILURE;
    }
}
