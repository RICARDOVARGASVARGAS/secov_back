<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUploads
{
    // Sube un archivo al almacenamiento
    protected function uploadFile(UploadedFile $file, string $folder): ?string
    {
        if ($file) {
            $path = $file->store($folder, 'public');
            return $path;
        }

        return null;
    }

    //Elimina un archivo del almacenamiento si existe.
    protected function deleteFile(string $path): bool
    {
        // Verifica si el archivo existe antes de intentar eliminarlo
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false; // Retorna false si el archivo no existe
    }
}
