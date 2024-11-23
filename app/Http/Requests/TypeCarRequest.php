<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerTypeCar')) {
            $name = 'unique:type_cars,name';
        } elseif (request()->routeIs('updateTypeCar')) {
            $name =  'unique:type_cars,name,' . request()->route('item')->id;
        }

        return [
            'name' => ['required', $name]
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Marca',
        ];
    }
}
