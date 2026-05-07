<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class ApiImage
{
    // Converte il path salvato nel DB in un URL pubblico per il frontend.
    public static function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::url($path);
    }
}