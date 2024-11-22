<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'page' => ['required', 'integer', 'min:1'],
            'perPage' => ['required', 'integer', 'min:1', 'max:100'],
            'sort' => ['required', 'in:asc,desc'],
        ];
    }

    public function attributes(): array
    {
        return [
            'search' => 'Búsqueda',
            'page' => 'Página',
            'perPage' => 'Registros por página',
        ];
    }
}
