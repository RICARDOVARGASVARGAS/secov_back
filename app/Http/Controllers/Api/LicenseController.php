<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LicenseResource;
use App\Models\Driver;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HandlesFileUploads;

class LicenseController extends Controller
{
    use HandlesFileUploads;

    // Listar licencias de los conductores
    function getDriverLicenses(Driver $item)
    {
        $licenses = $item->licenses()->orderBy('id', 'desc')->get();

        $licensesWithStatus = $licenses->map(function ($license) {
            $license->status = $license->renewal_date >= now()->toDateString() ? 'active' : 'expired';
            return $license;
        });

        return LicenseResource::collection($licensesWithStatus)->additional([
            'message' => 'Licencias Obtenidas.',
            'success' => true
        ]);
    }

    // Obtener licencia de un conductor
    function getDriverLicense(License $item)
    {
        return LicenseResource::make($item);
    }

    // Registrar licencia de un conductor
    function registerDriverLicense(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:licenses,number',
            'renewal_date' => 'required|date',
            'issue_date' => 'required|date',
            'class' => 'required',
            'category' => 'required',
            'driver_id' => 'required|exists:drivers,id',
            'file' => 'nullable|file', // Asegúrate de validar que es un archivo
        ], [], [
            'number' => 'Número de Licencia',
            'renewal_date' => 'Fecha de Vencimiento',
            'issue_date' => 'Fecha de Emisión',
            'class' => 'Clase',
            'category' => 'Categoría',
            'driver_id' => 'Conductor',
            'file' => 'Archivo',
        ]);

        // $filePath = null;
        // if ($request->hasFile('file')) {
        //     $filePath = $this->uploadFile($request->file('file'), 'licenses');
        // }

        $license = License::create([
            'number' => $request->number,
            'renewal_date' => $request->renewal_date,
            'issue_date' => $request->issue_date,
            'class' => $request->class,
            'category' => $request->category,
            'driver_id' => $request->driver_id,
            // 'file' => $filePath,
        ]);

        return LicenseResource::make($license)->additional([
            'message' => 'Licencia Registrada.'
        ]);
    }

    // Actualizar licencia de un conductor
    function updateDriverLicense(Request $request, License $license)
    {
        $request->validate([
            'number' => 'required|unique:licenses,number,' . $license->id,
            'renewal_date' => 'required|date',
            'issue_date' => 'required|date',
            'class' => 'required',
            'category' => 'required',
            'driver_id' => 'required|exists:drivers,id',
            'file' => 'nullable|file',
        ], [], [
            'number' => 'Número de Licencia',
            'renewal_date' => 'Fecha de Vencimiento',
            'issue_date' => 'Fecha de Emisión',
            'class' => 'Clase',
            'category' => 'Categoría',
            'driver_id' => 'Conductor',
            'file' => 'Archivo',
        ]);

        // $filePath = $license->file;
        // if ($request->hasFile('file')) {
        //     // Eliminar el archivo antiguo si existe
        //     if ($filePath) {
        //         $this->deleteFile($filePath);
        //     }
        //     // Subir el nuevo archivo
        //     $filePath = $this->uploadFile($request->file('file'), 'licenses');
        // }

        $license->update([
            'number' => $request->number,
            'renewal_date' => $request->renewal_date,
            'issue_date' => $request->issue_date,
            'class' => $request->class,
            'category' => $request->category,
            'driver_id' => $request->driver_id,
            // 'file' => $filePath,
        ]);

        return LicenseResource::make($license)->additional([
            'message' => 'Licencia Actualizada.',
        ]);
    }

    // Eliminar licencia de un conductor
    function deleteDriverLicense($license)
    {
        $license = License::find($license);
        if (!$license) {
            return response()->json([
                'message' => 'Licencia No Encontrada.',
                'status' => 404,
            ], 404);
        }

        try {
            DB::beginTransaction();
            // Eliminar el archivo asociado si existe
            if ($license->file) {
                $this->deleteFile($license->file);
            }
            $license->delete();
            DB::commit();
            return LicenseResource::make($license)->additional(([
                'message' => 'Licencia Borrada.',
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Licencia No Borrada.',
                'status' => 500,
            ], 500);
        }
    }
}
