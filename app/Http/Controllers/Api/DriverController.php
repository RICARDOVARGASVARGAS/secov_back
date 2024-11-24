<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    function getDrivers(ListRequest $request)
    {
        $items = Driver::included()->where('document_number', 'like', '%' . $request->search . '%')
            ->orWhere('name', 'like', '%' . $request->search . '%')
            ->orWhere('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->orWhere('license_number', 'like', '%' . $request->search . '%')
            ->orderBy('id', $request->sort);
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return DriverResource::collection($items);
    }

    function registerDriver(DriverRequest $request)
    {
        $item = Driver::create([
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'name' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'gender' => $request->gender,
            'license_number' => $request->license_number,
            'license_expiration_date' => $request->license_expiration_date,
            'license_issue_date' => $request->license_issue_date,
            'license_class' => $request->license_class,
            'license_category' => $request->license_category,
        ]);

        return DriverResource::make($item)->additional([
            'message' => 'Conductor Registrado.',
        ]);
    }

    function getDriver($item)
    {
        $item = Driver::included()->find($item);
        return DriverResource::make($item);
    }

    function updateDriver(DriverRequest $request, Driver $item)
    {
        $item->update([
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'name' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'gender' => $request->gender,
            'license_number' => $request->license_number,
            'license_expiration_date' => $request->license_expiration_date,
            'license_issue_date' => $request->license_issue_date,
            'license_class' => $request->license_class,
            'license_category' => $request->license_category,
        ]);

        return DriverResource::make($item)->additional([
            'message' => 'Conductor Actualizado.'
        ]);
    }

    function deleteDriver(Driver $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return DriverResource::make($item)->additional(([
                'message' => 'Conductor Borrado.',
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Driver No Borrado.',
                'status' => 500,
            ], 500);
        }
    }
}
