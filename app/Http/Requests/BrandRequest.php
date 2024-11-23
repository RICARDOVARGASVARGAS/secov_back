<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerBrand')) {
            $name = 'unique:brands,name';
        } elseif (request()->routeIs('updateBrand')) {
            $name =  'unique:brands,name,' . request()->route('item')->id;
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
