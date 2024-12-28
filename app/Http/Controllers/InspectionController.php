<?php

namespace App\Http\Controllers;

use App\Http\Resources\InspectionResource;
use App\Models\Inspection;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    // Obtener los Inspecciones de un vehículo
    public function getInspections($car_id)
    {
        $item = Inspection::included()->where('car_id', $car_id)->orderBy('id', 'desc')->get();
        return InspectionResource::collection($item);
    }

    // Obtener un Inspección en especifico
    public function getInspection($id)
    {
        $item = Inspection::included()->where('id', $id)->first();
        return InspectionResource::make($item);
    }

    // Registrar un Inspección
    public function registerInspection(Request $request)
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

        $item = Inspection::create([
            'issue_date' => $request->issue_date,
            'expiration_date' => $request->expiration_date,
            'car_id' => $request->car_id,
        ]);

        return InspectionResource::make($item)->additional([
            'message' => 'Inspección Registrada.'
        ]);
    }

    // Actualizar un Inspección
    public function updateInspection(Request $request, Inspection $item)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'expiration_date' => 'required|date',
            'file_inspection' => 'nullable',
            'car_id' => 'required|exists:cars,id',
        ], [], [
            'issue_date' => 'Fecha de Emisión',
            'expiration_date' => 'Fecha de Vencimiento',
            'file_inspection' => 'Archivo',
            'car_id' => 'Vehículo',
        ]);

        $item->update([
            'issue_date' => $request->issue_date,
            'expiration_date' => $request->expiration_date,
            'file_inspection' => $request->file_inspection,
            'car_id' => $request->car_id,
        ]);

        return InspectionResource::make($item)->additional([
            'message' => 'Inspección Actualizada.'
        ]);
    }

    // Eliminar un Inspección
    public function deleteInspection(Inspection $item)
    {
        $item->delete();

        return InspectionResource::make($item)->additional([
            'message' => 'Inspección Eliminada.'
        ]);
    }
}
