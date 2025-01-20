<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarController extends Controller
{
    // Listar vehículos
    function getCars(ListRequest $request)
    {
        $items = Car::included()->where('plate', 'like', '%' . $request->search . '%')
            ->orWhere('motor', 'like', '%' . $request->search . '%')
            ->orderBy('id', $request->sort);
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return CarResource::collection($items);
    }

    // Registrar un vehículo
    function registerCar(CarRequest $request)
    {
        $item = Car::create([
            'plate' => $request->plate,
            'chassis' => $request->chassis,
            'motor' => $request->motor,
            'brand_id' => $request->brand_id,
            'type_car_id' => $request->type_car_id,
            'group_id' => $request->group_id,
            'year_id' => $request->year_id,
            'color_id' => $request->color_id,
            'example_id' => $request->example_id,
            'driver_id' => $request->driver_id,
            'group_number' => $request->group_number,
            'number_of_seats' => $request->number_of_seats,
        ]);

        return CarResource::make($item)->additional([
            'message' => 'Vehículo Registrado.',
        ]);
    }

    // Obtener un vehículo
    function getCar($item)
    {
        $item = Car::included()->find($item);
        return CarResource::make($item);
    }

    // Actualizar un vehículo
    function updateCar(CarRequest $request, Car $item)
    {
        $item->update([
            'plate' => $request->plate,
            'chassis' => $request->chassis,
            'motor' => $request->motor,
            'image_car' => $request->image_car,
            'brand_id' => $request->brand_id,
            'type_car_id' => $request->type_car_id,
            'group_id' => $request->group_id,
            'year_id' => $request->year_id,
            'color_id' => $request->color_id,
            'example_id' => $request->example_id,
            'driver_id' => $request->driver_id,
            'group_number' => $request->group_number,
            'number_of_seats' => $request->number_of_seats,
            'file_car' => $request->file_car
        ]);

        return CarResource::make($item)->additional([
            'message' => 'Vehículo Actualizado.'
        ]);
    }

    // Eliminar un vehículo
    function deleteCar(Car $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return CarResource::make($item)->additional(([
                'message' => 'Vehículo Eliminado.',
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Car No Eliminado.',
                'status' => 500,
            ], 500);
        }
    }

    // Devolver la lista de vehículos de un conductor
    function getCarsByDriver($driver_id)
    {
        $item = Car::included()
            ->with(['brand', 'typeCar', 'group', 'year', 'color', 'example', 'latestInsurance', 'latestPermit', 'latestInspection'])
            ->where('driver_id', $driver_id)
            ->orderBy('id', 'desc')
            ->get();
        return CarResource::collection($item);
    }

    // Permisos de circulación

    //  Lista de vehículos que tienen todo en regla
    // function carsInCompliance()
    // {
    //     $carsInCompliance = Car::whereHas('latestPermit', function ($query) {
    //         $query->where('expiration_date', '>=', Carbon::now());
    //     })
    //         ->whereHas('latestInsurance', function ($query) {
    //             $query->where('expiration_date', '>=', Carbon::now());
    //         })
    //         ->whereHas('latestInspection', function ($query) {
    //             $query->where('expiration_date', '>=', Carbon::now());
    //         })
    //         ->whereHas('driver.latestLicense', function ($query) {
    //             $query->where('renewal_date', '>=', Carbon::now());
    //         })
    //         ->with([
    //             'latestPermit',
    //             'latestInsurance',
    //             'latestInspection',
    //             'driver.latestLicense'
    //         ])
    //         ->get();
    // }

    // Verificar si un vehículo específico cumple con las condiciones de circulación
    // public function checkCarCompliance($carId)
    // {

    //     $car = Car::with([
    //         'latestPermit',
    //         'latestInsurance',
    //         'latestInspection',
    //         'driver.latestLicense'
    //     ])->find($carId);

    //     if ($car) {
    //         $isInCompliance =
    //             optional($car->latestPermit)->expiration_date >= Carbon::now() &&
    //             optional($car->latestInsurance)->expiration_date >= Carbon::now() &&
    //             optional($car->latestInspection)->expiration_date >= Carbon::now() &&
    //             optional($car->driver->latestLicense)->renewal_date >= Carbon::now();

    //         return response()->json([
    //             'car_id' => $car->id,
    //             'in_compliance' => $isInCompliance,
    //             'details' => $car
    //         ]);
    //     } else {
    //         return response()->json(['message' => 'Vehicle not found'], 404);
    //     }
    // }

    // Lista de todos los vehículos con un campo adicional indicando si cumplen o no
    // public function allCarsCompliance()
    // {
    //     $cars = Car::with([
    //         'latestPermit',
    //         'latestInsurance',
    //         'latestInspection',
    //         'driver.latestLicense'
    //     ])
    //         ->get()
    //         ->map(function ($car) {
    //             $isInCompliance =
    //                 optional($car->latestPermit)->expiration_date >= Carbon::now() &&
    //                 optional($car->latestInsurance)->expiration_date >= Carbon::now() &&
    //                 optional($car->latestInspection)->expiration_date >= Carbon::now() &&
    //                 optional($car->driver->latestLicense)->renewal_date >= Carbon::now();

    //             $car->in_compliance = $isInCompliance; // Campo adicional
    //             return $car;
    //         });
    // }
}
