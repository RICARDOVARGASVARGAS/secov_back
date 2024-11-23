<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\TypeCarRequest;
use App\Http\Resources\TypeCarResource;
use App\Models\TypeCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeCarController extends Controller
{
    function getTypeCars(ListRequest $request)
    {
        $items = TypeCar::included()->where('name', 'like', '%' . $request->search . '%')->orderBy('id', $request->sort);
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return TypeCarResource::collection($items);
    }

    function registerTypeCar(TypeCarRequest $request)
    {
        $item = TypeCar::create([
            'name' => $request->name,
        ]);

        return TypeCarResource::make($item)->additional([
            'message' => 'Tipo de Vehículo Registrada.',
        ]);
    }

    function getTypeCar($item)
    {
        $item = TypeCar::included()->find($item);
        return TypeCarResource::make($item);
    }

    function updateTypeCar(TypeCarRequest $request, TypeCar $item)
    {
        $item->update([
            'name' => $request->name
        ]);

        return TypeCarResource::make($item)->additional([
            'message' => 'Tipo de Vehículo Actualizada.'
        ]);
    }

    function deleteTypeCar(TypeCar $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return TypeCarResource::make($item);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Tipo de Vehículo No Borrada.',
                'status' => 500,
            ], 500);
        }
    }
}
