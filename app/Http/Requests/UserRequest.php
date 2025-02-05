<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerUser')) {
            $document = 'unique:user,document';
            $email = 'unique:user,email';
        } elseif (request()->routeIs('updateUser')) {
            $document =  'unique:user,document,' . request()->route('item')->id;
            $email =  'unique:user,email,' . request()->route('item')->id;
        }

        return [
            'document' => ['required', $document],
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name' => ['required', 'string', 'min:3', 'max:50'],
            'image' => ['nullable'],
            'email' => ['required', $email],
            'phone_number' => ['nullable', 'string', 'min:3', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['required', 'boolean'],
            'visible' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'document' => 'Documento',
            'name' => 'Nombre',
            'first_name' => 'Apellido Paterno',
            'last_name' => 'Apellido Materno',
            'image' => 'Imagen',
            'email' => 'Correo Electrónico',
            'phone_number' => 'Numero de Teléfono',
            'password' => 'Contraseña',
            'is_active' => 'Activo',
            'visible' => 'Visible',
        ];
    }
}
