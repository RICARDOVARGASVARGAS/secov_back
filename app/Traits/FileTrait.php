<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    function saveFile($file, $path)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        // $fileName = uniqid() . '-' . $file->getClientOriginalName();
        // time() . '-' . $file->getClientOriginalName()
        // $path =  $file->move('storage/' . $path, $fileName);
        $path = $file->storeAs($path, $fileName);
        return $path;
    }

    function deleteFile($path)
    {
        Storage::delete($path);
    }
}
