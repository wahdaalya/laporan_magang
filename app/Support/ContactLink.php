<?php

namespace App\Support;

final class ContactLink
{
    public static function whatsappUrl(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }
}
