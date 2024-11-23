<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\YearRequest;
use App\Http\Resources\YearResource;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YearController extends Controller
{
    function getYears(ListRequest $request)
    {
        $items = Year::included()->where('name', 'like', '%' . $request->search . '%')->orderBy('id', $request->sort);
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return YearResource::collection($items);
    }

    function registerYear(YearRequest $request)
    {
        $item = Year::create([
            'name' => $request->name,
        ]);

        return YearResource::make($item)->additional([
            'message' => 'Año Registrado.',
        ]);
    }

    function getYear($item)
    {
        $item = Year::included()->find($item);
        return YearResource::make($item);
    }

    function updateYear(YearRequest $request, Year $item)
    {
        $item->update([
            'name' => $request->name
        ]);

        return YearResource::make($item)->additional([
            'message' => 'Año Actualizado.'
        ]);
    }

    function deleteYear(Year $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return YearResource::make($item);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Año No Borrada.',
                'status' => 500,
            ], 500);
        }
    }
}
