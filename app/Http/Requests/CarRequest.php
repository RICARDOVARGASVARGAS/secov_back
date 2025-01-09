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
            $chassis = 'unique:cars,chassis';
            $motor = 'unique:cars,motor';
        } elseif (request()->routeIs('updateCar')) {
            $plate =  'unique:cars,plate,' . request()->route('item')->id;
            $chassis =  'unique:cars,chassis,' . request()->route('item')->id;
            $motor =  'unique:cars,motor,' . request()->route('item')->id;
        }
        return [
            'plate' => ['required', $plate],
            'chassis' => ['nullable', $chassis, 'string', 'min:3', 'max:50'],
            'motor' => ['nullable', $motor, 'string', 'min:3', 'max:50'],
            'image_car' => ['nullable'],
            'brand_id' => ['required', 'exists:brands,id'],
            'type_car_id' => ['required', 'exists:type_cars,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'year_id' => ['required', 'exists:years,id'],
            'color_id' => ['required', 'exists:colors,id'],
            'example_id' => ['required', 'exists:examples,id'],
            'driver_id' => ['required', 'exists:drivers,id'],
            'group_number' => ['nullable', 'string'],
            'number_of_seats' => ['required', 'integer'],
            'file_car' => ['nullable'],
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
            'group_id' => 'Asociación',
            'year_id' => 'Año',
            'color_id' => 'Color',
            'example_id' => 'Modelo/Clase',
            'driver_id' => 'Conductor',
            'group_number' => 'Número de Asociación',
            'number_of_seats' => 'Número de Asientos',
            'image_car' => 'Imagen',
        ];
    }
}
