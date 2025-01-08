<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'model' => ['required', 'string'],
            'model_id' => ['required', 'integer'],
            'model_storage' => ['required', 'string'],
            'token' => ['required', 'string'],
            'uuid' => ['required', 'string'],
        ];
    }
}
