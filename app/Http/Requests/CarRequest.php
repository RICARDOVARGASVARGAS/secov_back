<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('registerCar')) {
            $plate = 'unique:cars,plate';
        } elseif (request()->routeIs('updateCar')) {
            $plate =  'unique:cars,plate,' . request()->route('item')->id;
        }
        return [
            'plate' => ['required', $plate],
            'chassis' => ['required', 'string', 'min:3', 'max:50'],
            'motor' => ['required', 'string', 'min:3', 'max:50'],
            'file_car' => ['nullable'],
            'brand_id' => ['required', 'exists:brands,id'],
            'type_car_id' => ['required', 'exists:type_cars,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'year_id' => ['required', 'exists:years,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'example_id' => ['required', 'exists:examples,id'],
            'number_soat' => ['nullable'],
            'file_soat' => ['nullable'],
            'date_soat_issue' => ['nullable', 'date'],
            'date_soat_expiration' => ['nullable', 'date'],
            'file_technical_review' => ['nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'plate' => 'Placa',
            'chassis' => 'Chasis',
            'motor' => 'Motor',
            'file_car' => 'Archivo Vehículo',
            'brand_id' => 'Marca',
            'type_car_id' => 'Tipo de Vehículo',
            'group_id' => 'Grupo',
            'year_id' => 'Año',
            'color_id' => 'Color',
            'example_id' => 'Ejemplo',
            'number_soat' => 'Número de Soat',
            'file_soat' => 'Archivo Soat',
            'date_soat_issue' => 'Fecha de Emisión de Soat',
            'date_soat_expiration' => 'Fecha de Expiración de Soat',
            'file_technical_review' => 'Archivo de Revisión Técnica',
        ];
    }
}
