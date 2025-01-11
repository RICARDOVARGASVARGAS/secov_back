<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteFileRequest;
use App\Http\Requests\UploadFileRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    // Definir URL base para la API de archivos
    private $storageApiUrl = 'https://storage.sys-code.com/api';
    // private $storageApiUrl = 'http://storage_sync.test/api';

    // Subir Archivo 
    public function uploadFile(UploadFileRequest $request)
    {
        $item = app("App\\Models\\$request->model")->find($request->model_id);

        if (!$item) {
            return response()->json([
                'message' => 'Elemento no Encontrado',
            ], 404);
        }

        // Eliminar Imagen
        if ($item->{$request->model_storage}) {
            try {
                // Extraer el UUID de la URL almacenada
                $uuid = Str::match('/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})/', $item->{$request->model_storage});

                if ($uuid) {
                    // Enviar solicitud para eliminar el archivo existente
                    $response = Http::withHeaders([
                        'Authorization' => $request->token,
                        'Accept' => 'application/json',
                    ])->delete($this->storageApiUrl . '/files/' . $uuid);

                    if (!$response->successful()) {
                        // return response()->json([
                        //     'message' => 'Error al eliminar el archivo existente.',
                        //     'error' => $response->json(),
                        // ], $response->status());
                        // Actualizar el almacenamiento del modelo a null
                        $item->update([
                            $request->model_storage => null,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Ocurrió un error al intentar eliminar el archivo existente.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
        // Subir Archivo
        try {
            // Obtener el archivo del request
            $file = $request->file('file');

            // Preparar los datos para la solicitud HTTP
            $response = Http::withHeaders([
                'Authorization' => $request->token,
                'Accept' => 'application/json',
            ])->attach(
                'file',                                // Nombre del campo esperado por la API
                file_get_contents($file->getRealPath()), // Contenido del archivo
                $file->getClientOriginalName()         // Nombre original del archivo
            )->post($this->storageApiUrl . '/files/upload', [
                'storage' => $request->storage,        // Ruta de almacenamiento
            ]);

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $data = $response->json();

                // Guardar la información del archivo en la base de datos
                $item->update([
                    $request->model_storage => $data['file']['url'],
                ]);

                return response()->json([
                    'message' => $data['message'],
                    'file' => $data['file'],
                ], 201);
            }

            // Manejar errores de la API
            return response()->json([
                'message' => 'Error al subir el archivo.',
                'error' => $response->json(),
            ], $response->status());
            // Respuesta:
            // {
            //     "message": "Archivo subido correctamente.",
            //     "file": {
            //         "name": "PLANES.jpg",
            //         "path": "proyecto-base/Conductor/34f3f5eb-1010-49e6-aa19-9916a9affd4a.jpg",
            //         "uuid": "34f3f5eb-1010-49e6-aa19-9916a9affd4a",
            //         "size": 60610,
            //         "type": "jpg",
            //         "url": "http://storage_sync.test/storage/proyecto-base/Conductor/34f3f5eb-1010-49e6-aa19-9916a9affd4a.jpg",
            //         "project_id": 1,
            //         "updated_at": "2025-01-08T16:04:38.000000Z",
            //         "created_at": "2025-01-08T16:04:38.000000Z",
            //         "id": 3
            //     }
            // }

            // Error
            // {
            //     "message": "Error al subir el archivo.",
            //     "error": {
            //         "message": "No se encontró un Token valido."
            //     }
            // }
        } catch (\Exception $e) {
            // Manejo de errores en el servidor
            return response()->json([
                'message' => 'Ocurrió un error inesperado.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar Archivo
    public function deleteFile(DeleteFileRequest $request)
    {
        $item = app("App\\Models\\$request->model")->find($request->model_id);

        if (!$item) {
            return response()->json([
                'message' => 'Elemento no Encontrado',
            ], 404);
        }

        try {
            // Preparar los datos para la solicitud HTTP
            $response = Http::withHeaders([
                'Authorization' => $request->token,
                'Accept' => 'application/json',
            ])->delete($this->storageApiUrl . '/files/' . $request->uuid);

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $data = $response->json();

                // Guardar la información del archivo en la base de datos
                $item->update([
                    $request->model_storage => null,
                ]);

                return response()->json([
                    'message' => $data['message'],
                    'file' => $data['file'],
                ], 200);
            }

            // Manejar errores de la API
            return response()->json([
                'message' => 'Error al eliminar el archivo.',
                'error' => $response->json(),
            ], $response->status());
        } catch (\Exception $e) {
            // Manejo de errores en el servidor
            return response()->json([
                'message' => 'Ocurrido un error inesperado.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
