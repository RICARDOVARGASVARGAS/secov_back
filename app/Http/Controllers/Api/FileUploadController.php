<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandlesFileUploads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    use HandlesFileUploads;

    // Sube un archivo y lo asocia a un modelo específico.
    public function upload(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'file' => 'required|file',
            'folder' => 'required|string',
            'field' => 'required|string',
        ], [], [
            'model' => 'Modelo',
            'id' => 'ID',
            'file' => 'Archivo',
            'folder' => 'Carpeta',
            'field' => 'Campo',
        ]);

        $modelClass = 'App\\Models\\' . $request->input('model');
        $model = $modelClass::findOrFail($request->input('id'));

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folder = $request->input('folder');
            $field = $request->input('field');

            // Elimina el archivo anterior si existe
            if ($model->$field) {
                $this->deleteFile($model->$field);
            }

            // Sube el nuevo archivo
            $path = $this->uploadFile($file, $folder);

            // Actualiza el modelo con la nueva ruta del archivo
            $model->$field = $path;
            $model->save();

            // Genera la URL completa del archivo
            $url = Storage::url($path);

            return response()->json([
                'message' => 'Archivo subido correctamente',
                'path' => $path,
                'url' => $url, // URL completa para acceder al archivo
            ], 200);
        }

        return response()->json(['message' => 'Archivo no subido'], 400);
    }

    // Elimina un archivo asociado a un modelo.
    public function delete(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'field' => 'required|string',
        ], [], [
            'model' => 'Modelo',
            'id' => 'ID',
            'field' => 'Campo',
        ]);

        $modelClass = 'App\\Models\\' . $request->input('model');
        $model = $modelClass::findOrFail($request->input('id'));
        $field = $request->input('field');

        // Verifica si el campo tiene un archivo asociado
        if ($model->$field) {
            // Elimina el archivo del almacenamiento
            $this->deleteFile($model->$field);

            // Elimina la referencia del archivo en la base de datos
            $model->$field = null;
            $model->save();

            return response()->json([
                'message' => 'Archivo eliminado correctamente',
            ], 200);
        }

        return response()->json(['message' => 'No hay archivo para eliminar'], 400);
    }
}
