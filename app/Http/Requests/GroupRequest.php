<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerGroup')) {
            $name = 'unique:groups,name';
        } elseif (request()->routeIs('updateGroup')) {
            $name =  'unique:groups,name,' . request()->route('item')->id;
        }

        return [
            'name' => ['required', $name],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Marca',
            'description' => 'Descripción',
            'image' => 'Imagen',
        ];
    }
}
