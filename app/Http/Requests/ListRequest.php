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
            'perPage' => ['required', 'string', 'regex:/^(all|[1-9][0-9]?)$/'], // Permite 'all' o números positivos
            'sort' => ['required', 'in:asc,desc'],
        ];
    }

    public function attributes(): array
    {
        return [
            'search' => 'Búsqueda',
            'page' => 'Página',
            'perPage' => 'Registros por página',
            'sort' => 'Orden',
        ];
    }
}
