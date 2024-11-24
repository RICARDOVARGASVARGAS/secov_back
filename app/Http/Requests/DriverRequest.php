<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerDriver')) {
            $document_number = 'unique:drivers,document_number';
        } elseif (request()->routeIs('updateDriver')) {
            $document_number =  'unique:drivers,document_number,' . request()->route('item')->id;
        }

        return [
            'document_type' => ['required', 'in:pasaporte,dni'],
            'document_number' => ['required', $document_number],
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'first_name' => ['required', 'string', 'min:3', 'max:50'],
            'last_name' => ['required', 'string', 'min:3', 'max:50'],
            'birth_date' => ['required', 'date'],
            'image' => ['nullable'],
            'email' => ['nullable', 'email'],
            'phone_number' => ['nullable', 'regex:/^[0-9]{6,15}$/'],
            'address' => ['nullable'],
            'gender' => ['required', 'in:masculino,femenino'],
            'license_number' => ['nullable'],
            'license_expiration_date' => ['nullable', 'date'],
            'license_issue_date' => ['nullable', 'date'],
            'license_class' => ['nullable'],
            'license_category' => ['nullable'],
            'image_license' => ['nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'document_type' => 'Tipo de Documento',
            'document_number' => 'Numero de Documento',
            'name' => 'Nombre',
            'first_name' => 'Apellido Paterno',
            'last_name' => 'Apellido Materno',
            'birth_date' => 'Fecha de Nacimiento',
            'image' => 'Imagen',
            'email' => 'Email',
            'phone_number' => 'Numero de Teléfono',
            'address' => 'Dirección',
            'gender' => 'Genero',
            'license_number' => 'Numero de Licencia',
            'license_expiration_date' => 'Fecha de Expiración de Licencia',
            'license_issue_date' => 'Fecha de Emisión de Licencia',
            'license_class' => 'Clase de Licencia',
            'license_category' => 'Categoría de Licencia',            
            'image_license' => 'Imagen de Licencia',    
        ];
    }
}
