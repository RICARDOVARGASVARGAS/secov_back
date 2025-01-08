<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv,pdf', 'max:20480'], // 20MB máximo
            'model' => ['required', 'string'],
            'model_id' => ['required', 'integer'],
            'model_storage' => ['required', 'string'],
            'token' => ['required', 'string'],
            'storage' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'Archivo',
            'model' => 'Modelo',
            'model_id' => 'ID del modelo',
            'model_storage' => 'Almacenamiento del modelo en la base de datos',
            'token' => 'Token',
            'storage' => 'Almacenamiento del archivo en la nube',
        ];
    }
}
