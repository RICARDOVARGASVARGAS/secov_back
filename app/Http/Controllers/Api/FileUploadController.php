<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use App\Traits\HandlesFileUploads;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    use HandlesFileUploads, GeneratesUrls;

    // Sube un archivo y lo asocia a un modelo específico.
    public function upload(Request $request)
    {
        // Validación de los datos de entrada
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

        // Obtener el modelo y su instancia
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
            $relativeUrl = Storage::url($path); // Ruta relativa del archivo
            $fullUrl = $this->getFullUrl($path); // URL completa para acceder al archivo

            // Agrega un campo dinámico al modelo con el nombre "field_url"
            $model->setAttribute($field . '_url', $fullUrl);

            // Devuelve la respuesta JSON con el modelo actualizado
            return response()->json([
                'message' => 'Archivo subido correctamente',
                'path' => $path,
                'url' => $fullUrl,
                'success' => true,
                'item' => $model, // El modelo incluirá el campo dinámico "field_url"
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
                'success' => true,
                'item' => $model
            ], 200);
        }

        return response()->json(['message' => 'No hay archivo para eliminar'], 400);
    }
}
