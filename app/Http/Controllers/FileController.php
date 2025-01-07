<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    // Definir URL base para la API de archivos
    private $storageApiUrl = 'https://storage.sys-code.com/api/files';

    // Subir Archivo
    public function uploadFile(Request $request)
    {
        // Validación de entrada
        $request->validate($this->getUploadValidationRules());

        $item = $this->findModel($request->model, $request->model_id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Eliminar archivo previo si existe
        $this->deleteFileIfExists($item, $request);

        // Subir nuevo archivo
        try {
            $file = $request->file('file');
            $response = $this->uploadToStorage($file, $request->api_key, $request->storage);

            if ($response->successful()) {
                $data = $response->json();
                $item->update([$request->model_storage => $data['file']['url']]);

                return response()->json([
                    'message' => 'File uploaded and model updated successfully.',
                    'file' => $data['file'],
                    'item' => $item
                ], 200);
            } else {
                return $this->handleFileUploadFailure($response);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while uploading the file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar Archivo
    public function deleteFile(Request $request)
    {
        $request->validate($this->getDeleteValidationRules());

        $item = $this->findModel($request->model, $request->model_id);
        if (!$item || !$item->{$request->model_storage}) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $response = $this->deleteFromStorage($request->encode_url_file, $request->api_key);

        if ($response->successful()) {
            $item->update([$request->model_storage => null]);
            return response()->json(['message' => 'File deleted and model updated successfully.'], 200);
        } else {
            return $this->handleFileDeletionFailure($response);
        }
    }

    // Métodos Auxiliares

    // Buscar el modelo
    private function findModel($model, $model_id)
    {
        return app("App\\Models\\$model")->find($model_id);
    }

    // Eliminar archivo si existe en el modelo
    private function deleteFileIfExists($item, $request)
    {
        if ($item->{$request->model_storage}) {
            $encode = base64_encode($item->{$request->model_storage});
            $this->deleteFromStorage($encode, $request->api_key);
            $item->update([$request->model_storage => null]);
        }
    }

    // Subir archivo al almacenamiento
    private function uploadToStorage($file, $apiKey, $storage)
    {
        return Http::withHeaders([
            'Authorization' => $apiKey,
            'Accept' => 'application/json',
        ])->attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post($this->storageApiUrl . '/upload', [
            'location' => $storage,
        ]);
    }

    // Eliminar archivo del almacenamiento
    private function deleteFromStorage($encodeUrlFile, $apiKey)
    {
        return Http::withHeaders([
            'Authorization' => $apiKey,
            'Accept' => 'application/json',
        ])->delete($this->storageApiUrl . '/' . $encodeUrlFile);
    }

    // Manejar error en carga de archivo
    private function handleFileUploadFailure($response)
    {
        return response()->json([
            'message' => 'Failed to upload file to external API.',
            'error' => $response->json(),
        ], $response->status());
    }

    // Manejar error en eliminación de archivo
    private function handleFileDeletionFailure($response)
    {
        return response()->json([
            'message' => 'Failed to delete file from external API.',
            'error' => $response->json(),
        ], $response->status());
    }

    // Validación para la subida de archivos
    private function getUploadValidationRules()
    {
        return [
            'file' => ['required', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv,pdf', 'max:20480'], // 20MB máximo
            'model' => ['required', 'string'],
            'model_id' => ['required', 'integer'],
            'model_storage' => ['required', 'string'],
            'api_key' => ['required', 'string'],
            'storage' => ['required', 'string'],
        ];
    }

    // Validación para la eliminación de archivos
    private function getDeleteValidationRules()
    {
        return [
            'model' => ['required', 'string'],
            'model_id' => ['required', 'integer'],
            'model_storage' => ['required', 'string'],
            'api_key' => ['required', 'string'],
            'encode_url_file' => ['required', 'string'],
        ];
    }
    // MI CÓDIGO
    // // Subir Archivo
    // function uploadFile(Request $request)
    // {
    //     $request->validate([
    //         'file' => ['required', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv,pdf', 'max:20480'], // 20MB máximo
    //         'model' => ['required', 'string'],
    //         'model_id' => ['required', 'integer'],
    //         'model_storage' => ['required', 'string'],
    //         'api_key' => ['required', 'string'],
    //         'storage' => ['required', 'string'],
    //     ]);

    //     $item = app("App\\Models\\$request->model")->find($request->model_id);

    //     if (!$item) {
    //         return response()->json([
    //             'message' => 'Item not found',
    //         ], 404);
    //     }

    //     // Eliminar Imagen
    //     if ($item->{$request->model_storage}) {
    //         $encode = base64_encode($item->{$request->model_storage});
    //         $response = $this->delete($encode, $request->api_key);

    //         if ($response->successful()) {
    //             $item->update([
    //                 $request->model_storage => null, // Asegúrate de que el modelo tenga este campo
    //             ]);
    //         }
    //     }

    //     // Subir Imagen
    //     try {
    //         // Preparar el archivo para la subida
    //         $file = $request->file('file');

    //         // Realizar la solicitud HTTP
    //         $response = Http::withHeaders([
    //             'Authorization' => $request->api_key,
    //             'Accept' => 'application/json',
    //         ])->attach(
    //             'file', // Nombre del campo esperado por la API
    //             file_get_contents($file->getRealPath()), // Contenido del archivo
    //             $file->getClientOriginalName() // Nombre original del archivo
    //         )->post('https://storage.sys-code.com/api/files/upload', [
    //             'location' => $request->storage,
    //         ]);

    //         // Verificar la respuesta de la API
    //         if ($response->successful()) {
    //             $data = $response->json();

    //             // Actualizar el modelo con la URL
    //             $item->update([
    //                 $request->model_storage => $data['file']['url'], // Asegúrate de que el modelo tenga este campo
    //             ]);

    //             return response()->json([
    //                 'message' => 'File uploaded and model updated successfully.',
    //                 'file' => $data['file'],
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'Failed to upload file to external API.',
    //                 'error' => $response->json(),
    //             ], $response->status());
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'An error occurred while uploading the file.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // // Eliminar Archivo
    // function deleteFile(Request $request)
    // {
    //     $request->validate([
    //         'model' => ['required', 'string'],
    //         'model_id' => ['required', 'integer'],
    //         'model_storage' => ['required', 'string'],
    //         'api_key' => ['required', 'string'],
    //         'encode_url_file' => ['required', 'string'],
    //     ]);

    //     $item = app("App\\Models\\$request->model")->find($request->model_id);

    //     if (!$item) {
    //         return response()->json([
    //             'message' => 'Item not found',
    //         ], 404);
    //     }
    //     if (!$item->{$request->model_storage}) {
    //         return response()->json([
    //             'message' => 'File not found',
    //         ], 404);
    //     }

    //     $response = $this->delete($request->encode_url_file, $request->api_key);

    //     if ($response->successful()) {
    //         $item->update([
    //             $request->model_storage => null, // Asegúrate de que el modelo tenga este campo
    //         ]);

    //         return response()->json([
    //             'message' => 'File deleted and model updated successfully.',
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Failed to delete file from external API.',
    //             'error' => $response->json(),
    //         ], $response->status());
    //     }
    // }

    // // Borrar Archivo
    // function delete($encode_url_file, $api_key)
    // {
    //     // $encode = base64_encode($encode_url_file);
    //     try {
    //         $response = Http::withHeaders([
    //             'Accept' => 'application/json',
    //             'Authorization' => $api_key
    //         ])->delete('https://storage.sys-code.com/api/files/' . $encode_url_file);

    //         return $response;
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'An error occurred while deleting the file.',
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }
}
