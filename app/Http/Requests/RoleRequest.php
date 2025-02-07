<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('rol.registerRole')) {
            $name = 'unique:roles,name';
        } elseif (request()->routeIs('rol.updateRole')) {
            $name =  'unique:roles,name,' . request()->route('role');
        }

        return [
            'name' => ['required', 'min:3', 'max:50', $name],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Rol',
        ];
    }
}
