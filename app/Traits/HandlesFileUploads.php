<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUploads
{
    protected function uploadFile(UploadedFile $file, string $folder): ?string
    {
        if ($file) {
            $path = $file->store($folder, 'public');
            return $path;
        }

        return null;
    }

    protected function deleteFile(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
