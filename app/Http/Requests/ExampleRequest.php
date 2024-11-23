<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExampleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerExample')) {
            $name = 'unique:examples,name';
        } elseif (request()->routeIs('updateExample')) {
            $name =  'unique:examples,name,' . request()->route('item')->id;
        }

        return [
            'name' => ['required', $name]
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Modelo',
        ];
    }
}
