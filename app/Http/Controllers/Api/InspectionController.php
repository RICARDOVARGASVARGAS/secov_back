<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\InspectionResource;
use App\Models\Inspection;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionController extends Controller
{
    use HandlesFileUploads;

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
    public function deleteInspection($item)
    {
        $item = Inspection::find($item);

        if (!$item) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        try {
            DB::beginTransaction();

            if ($item->file_inspection) {
                $this->deleteFile($item->file_inspection);
            }

            $item->delete();

            DB::commit();
            return InspectionResource::make($item)->additional([
                'message' => 'Inspección Eliminada.',
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Capturar excepciones (por ejemplo, restricciones de clave foránea)
            return response()->json([
                'message' => 'No se pudo eliminar el ítem debido a restricciones de la base de datos.',
                'error' => $e,
            ]);
        }
    }
}
