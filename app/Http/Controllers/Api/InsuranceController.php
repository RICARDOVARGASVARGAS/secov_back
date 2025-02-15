<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\InsuranceResource;
use App\Models\Insurance;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsuranceController extends Controller
{
    use HandlesFileUploads;
    
    // Obtener los seguros de un vehículo
    public function getInsurances($car_id)
    {
        $item = Insurance::included()->where('car_id', $car_id)->orderBy('id', 'desc')->get();
        return InsuranceResource::collection($item);
    }

    // Obtener un seguro en especifico
    public function getInsurance($id)
    {
        $item = Insurance::included()->where('id', $id)->first();
        return InsuranceResource::make($item);
    }

    // Registrar un seguro
    public function registerInsurance(Request $request)
    {
        $request->validate([
            'number_insurance' => 'required|unique:insurances,number_insurance',
            'issue_date' => 'required|date',
            'expiration_date' => 'required|date',
            'car_id' => 'required|exists:cars,id',
        ], [], [
            'number_insurance' => 'Número de Seguro',
            'issue_date' => 'Fecha de Emisión',
            'expiration_date' => 'Fecha de Vencimiento',
            'car_id' => 'Vehículo',
        ]);

        $item = Insurance::create([
            'number_insurance' => $request->number_insurance,
            'issue_date' => $request->issue_date,
            'expiration_date' => $request->expiration_date,
            'car_id' => $request->car_id,
        ]);

        return InsuranceResource::make($item)->additional([
            'message' => 'Seguro Registrado.'
        ]);
    }

    // Actualizar un seguro
    public function updateInsurance(Request $request, Insurance $item)
    {
        $request->validate([
            'number_insurance' => 'required|unique:insurances,number_insurance,' . $item->id,
            'issue_date' => 'required|date',
            'expiration_date' => 'required|date',
            'file_insurance' => 'nullable',
            'car_id' => 'required|exists:cars,id',
        ], [], [
            'number_insurance' => 'Número de Seguro',
            'issue_date' => 'Fecha de Emisión',
            'expiration_date' => 'Fecha de Vencimiento',
            'file_insurance' => 'Archivo',
            'car_id' => 'Vehículo',
        ]);

        $item->update([
            'number_insurance' => $request->number_insurance,
            'issue_date' => $request->issue_date,
            'expiration_date' => $request->expiration_date,
            'file_insurance' => $request->file_insurance,
            'car_id' => $request->car_id,
        ]);

        return InsuranceResource::make($item)->additional([
            'message' => 'Seguro Actualizado.'
        ]);
    }

    // Eliminar un seguro
    public function deleteInsurance($item)
    {
        $item = Insurance::find($item);

        if (!$item) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        try {
            DB::beginTransaction();

            if ($item->file_insurance) {
                $this->deleteFile($item->file_insurance);
            }

            $item->delete();

            DB::commit();
            return InsuranceResource::make($item)->additional([
                'message' => 'Seguro Eliminado.',
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
