<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    function getCars(ListRequest $request)
    {
        $items = Car::included()->where('plate', 'like', '%' . $request->search . '%')
            ->orWhere('chassis', 'like', '%' . $request->search . '%')
            ->orWhere('motor', 'like', '%' . $request->search . '%')
            ->orWhere('number_soat', 'like', '%' . $request->search . '%')
            ->orderBy('id', $request->sort);
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return CarResource::collection($items);
    }

    function registerCar(CarRequest $request)
    {
        $item = Car::create([
            'plate' => $request->plate,
            'chassis' => $request->chassis,
            'motor' => $request->motor,
            'file_car' => $request->file_car,
            'brand_id' => $request->brand_id,
            'type_car_id' => $request->type_car_id,
            'group_id' => $request->group_id,
            'year_id' => $request->year_id,
            'color_id' => $request->color_id,
            'example_id' => $request->example_id,
            'driver_id' => $request->driver_id,
            'number_soat' => $request->number_soat,
            'date_soat_issue' => $request->date_soat_issue,
            'date_soat_expiration' => $request->date_soat_expiration,
        ]);

        return CarResource::make($item)->additional([
            'message' => 'Vehículo Registrado.',
        ]);
    }

    function getCar($item)
    {
        $item = Car::included()->find($item);
        return CarResource::make($item);
    }

    function updateCar(CarRequest $request, Car $item)
    {
        $item->update([
            'plate' => $request->plate,
            'chassis' => $request->chassis,
            'motor' => $request->motor,
            'file_car' => $request->file_car,
            'brand_id' => $request->brand_id,
            'type_car_id' => $request->type_car_id,
            'group_id' => $request->group_id,
            'year_id' => $request->year_id,
            'color_id' => $request->color_id,
            'example_id' => $request->example_id,
            'driver_id' => $request->driver_id,
            'number_soat' => $request->number_soat,
            'date_soat_issue' => $request->date_soat_issue,
            'date_soat_expiration' => $request->date_soat_expiration,
        ]);

        return CarResource::make($item)->additional([
            'message' => 'Vehículo Actualizado.'
        ]);
    }

    function deleteCar(Car $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return CarResource::make($item)->additional(([
                'message' => 'Vehículo Borrado.',
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Car No Borrado.',
                'status' => 500,
            ], 500);
        }
    }

    // Devolver la lista de vehículos de un conductor
    function getCarsByDriver($driver_id)
    {
        $item = Car::included()->with(['brand', 'typeCar', 'group', 'year', 'color', 'example'])->where('driver_id', $driver_id)->get();
        return CarResource::collection($item);
    }
}
