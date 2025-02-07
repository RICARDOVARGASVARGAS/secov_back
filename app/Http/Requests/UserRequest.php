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
        if (request()->routeIs('user.registerUser')) {
            $document = 'unique:users,document';
            $email = 'unique:users,email';
        } elseif (request()->routeIs('user.updateUser')) {
            $document =  'unique:users,document,' . request()->route('user');
            $email =  'unique:users,email,' . request()->route('user');
        }

        return [
            'document' => ['required', 'min:8', $document],
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name' => ['required', 'string', 'min:3', 'max:50'],
            'image' => ['nullable'],
            'email' => ['required', $email],
            'phone_number' => ['nullable', 'string', 'min:3', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
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
