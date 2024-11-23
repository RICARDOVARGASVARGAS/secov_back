<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerYear')) {
            $name = 'unique:years,name';
        } elseif (request()->routeIs('updateYear')) {
            $name =  'unique:years,name,' . request()->route('item')->id;
        }

        return [
            'name' => ['required', $name]
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Año',
        ];
    }
}
