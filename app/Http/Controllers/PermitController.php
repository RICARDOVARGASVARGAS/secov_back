<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermitResource;
use App\Models\Permit;
use Illuminate\Http\Request;

class PermitController extends Controller
{
    // Obtener los permisos de un vehículo
    public function getPermits($car_id)
    {
        $item = Permit::included()->where('car_id', $car_id)->orderBy('id', 'desc')->get();
        return PermitResource::collection($item);
    }

    // Obtener un permiso en especifico
    public function getPermit($id)
    {
        $item = Permit::included()->where('id', $id)->first();
        return PermitResource::make($item);
    }

    // Registrar un permiso
    public function registerPermit(Request $request)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'expiration_date' => 'required|date',
            'car_id' => 'required|exists:cars,id',
        ], [], [
            'issue_date' => 'Fecha de Emisión',
            'expiration_date' => 'Fecha de Vencimiento',
            'car_id' => 'Vehículo',
        ]);

        $item = Permit::create([
            'issue_date' => $request->issue_date,
            'expiration_date' => $request->expiration_date,
            'car_id' => $request->car_id,
        ]);

        return PermitResource::make($item)->additional([
            'message' => 'Permiso Registrado.'
        ]);
    }

    // Actualizar un Permiso
    public function updatePermit(Request $request, Permit $item)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'expiration_date' => 'required|date',
            'file_permit' => 'nullable',
            'car_id' => 'required|exists:cars,id',
        ], [], [
            'issue_date' => 'Fecha de Emisión',
            'expiration_date' => 'Fecha de Vencimiento',
            'file_permit' => 'Archivo',
            'car_id' => 'Vehículo',
        ]);

        $item->update([
            'issue_date' => $request->issue_date,
            'expiration_date' => $request->expiration_date,
            'file_permit' => $request->file_permit,
            'car_id' => $request->car_id,
        ]);

        return PermitResource::make($item)->additional([
            'message' => 'Permiso Actualizado.'
        ]);
    }

    // Eliminar un Permiso
    public function deletePermit(Permit $item)
    {
        $item->delete();

        return PermitResource::make($item)->additional([
            'message' => 'Permiso Eliminado.'
        ]);
    }
}
