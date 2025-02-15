<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarController extends Controller
{

    use HandlesFileUploads;

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
    function deleteCar($item)
    {
        $item = Car::find($item);

        if (!$item) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        try {
            DB::beginTransaction();

            if ($item->file_car) {
                $this->deleteFile($item->file_car);
            }

            $item->delete();

            DB::commit();

            return response()->json(['message' => 'Vehículo Eliminado.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Ocurrió un error'], 500);
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
}
