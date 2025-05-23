<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\PermitResource;
use App\Models\Permit;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermitController extends Controller
{
    use HandlesFileUploads;
    
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
    public function deletePermit($item)
    {
        $item = Permit::find($item);

        if (!$item) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        try {
            DB::beginTransaction();

            if ($item->file_permit) {
                $this->deleteFile($item->file_permit);
            }

            $item->delete();

            DB::commit();
            return PermitResource::make($item)->additional([
                'message' => 'Permiso Eliminado.',
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
