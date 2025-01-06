<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    function uploadFile(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv,pdf', 'max:20480'], // 20MB máximo
            'model' => ['required', 'string'],
            'model_id' => ['required', 'integer'],
            'model_storage' => ['required', 'string'],
            'api_key' => ['required', 'string'],
            'storage' => ['required', 'string'],
        ]);

        $item = app("App\\Models\\$request->model")->find($request->model_id);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found',
            ], 404);
        }

        // Eliminar Imagen

        // Subir Imagen
        try {
            // Preparar el archivo para la subida
            $file = $request->file('file');

            // Realizar la solicitud HTTP
            $response = Http::withHeaders([
                'Authorization' => $request->api_key,
                'Accept' => 'application/json',
            ])->attach(
                'file', // Nombre del campo esperado por la API
                file_get_contents($file->getRealPath()), // Contenido del archivo
                $file->getClientOriginalName() // Nombre original del archivo
            )->post('https://storage.sys-code.com/api/files/upload', [
                'location' => $request->storage,
            ]);

            // Verificar la respuesta de la API
            if ($response->successful()) {
                $data = $response->json();

                // Actualizar el modelo con la URL
                $item->update([
                    $request->model_storage => $data['file']['url'], // Asegúrate de que el modelo tenga este campo
                ]);

                return response()->json([
                    'message' => 'File uploaded and model updated successfully.',
                    'image' => $data['file']['url'],
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed to upload file to external API.',
                    'error' => $response->json(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while uploading the file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
