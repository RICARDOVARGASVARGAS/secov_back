<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    function getColors(ListRequest $request)
    {
        $items = Color::included()->where('name', 'like', '%' . $request->search . '%')->orderBy('id', $request->sort);
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return ColorResource::collection($items);
    }

    function registerColor(ColorRequest $request)
    {
        $item = Color::create([
            'name' => $request->name,
            'hex' => $request->hex,
        ]);

        return ColorResource::make($item)->additional([
            'message' => 'Color Registrado.',
        ]);
    }

    function getColor($item)
    {
        $item = Color::included()->find($item);
        return ColorResource::make($item);
    }

    function updateColor(ColorRequest $request, Color $item)
    {
        $item->update([
            'name' => $request->name,
            'hex' => $request->hex,
        ]);

        return ColorResource::make($item)->additional([
            'message' => 'Color Actualizado.'
        ]);
    }

    function deleteColor(Color $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return ColorResource::make($item);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Color No Borrado.',
                'status' => 500,
            ], 500);
        }
    }
}
