<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerColor')) {
            $name = 'unique:color,name';
        } elseif (request()->routeIs('updateColor')) {
            $name =  'unique:color,name,' . request()->route('item')->id;
        }

        return [
            'name' => ['required', $name],
            'hex' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Color',
            'hex' => 'Hex',
        ];
    }
}
