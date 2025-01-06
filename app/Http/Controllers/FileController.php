<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    // Subir Archivo
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
        if ($item->{$request->model_storage}) {
            $encode = base64_encode($item->{$request->model_storage});
            $response = $this->delete($encode, $request->api_key);

            if ($response->successful()) {
                $item->update([
                    $request->model_storage => null, // Asegúrate de que el modelo tenga este campo
                ]);
            }
        }

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

    // Eliminar Archivo
    function deleteFile(Request $request)
    {
        $request->validate([
            'model' => ['required', 'string'],
            'model_id' => ['required', 'integer'],
            'model_storage' => ['required', 'string'],
            'api_key' => ['required', 'string'],
            'encode_url_file' => ['required', 'string'],
        ]);

        $item = app("App\\Models\\$request->model")->find($request->model_id);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found',
            ], 404);
        }
        if (!$item->{$request->model_storage}) {
            return response()->json([
                'message' => 'File not found',
            ], 404);
        }

        $response = $this->delete($request->encode_url_file, $request->api_key);

        if ($response->successful()) {
            $item->update([
                $request->model_storage => null, // Asegúrate de que el modelo tenga este campo
            ]);

            return response()->json([
                'message' => 'File deleted and model updated successfully.',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to delete file from external API.',
                'error' => $response->json(),
            ], $response->status());
        }
    }

    // Borrar Archivo
    function delete($encode_url_file, $api_key)
    {
        // $encode = base64_encode($encode_url_file);
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => $api_key
            ])->delete('https://storage.sys-code.com/api/files/' . $encode_url_file);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the file.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
