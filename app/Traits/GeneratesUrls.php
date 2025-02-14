<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait GeneratesUrls
{
    //  Genera una URL completa para un archivo almacenado.

    protected function getFullUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        // Combina la URL base de la aplicación con la ruta relativa del archivo
        // return config('app.url') . Storage::url($path);

        return Storage::url($path);
    }
}
