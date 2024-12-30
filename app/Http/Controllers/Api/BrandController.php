<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function getBrands(ListRequest $request)
    {
        $query = Brand::included()
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderBy('id', $request->sort);

        if ($request->perPage === 'all') {
            $items = $query->get(); // Obtén todos los resultados.
        } else {
            $items = $query->paginate(
                $request->perPage, // Cantidad por página.
                ['*'], // Selección de columnas.
                'page', // Nombre del parámetro de la página.
                $request->page // Página actual.
            );
        }

        return BrandResource::collection($items)->additional([
            'message' => 'Lista de Marcas.',
            'status' => 200
        ]);
    }


    function registerBrand(BrandRequest $request)
    {
        $item = Brand::create([
            'name' => $request->name,
        ]);

        return BrandResource::make($item)->additional([
            'message' => 'Marca Registrada.',
            'status' => 200
        ]);
    }

    function getBrand($item)
    {
        $item = Brand::included()->find($item);
        return BrandResource::make($item)->additional([
            'message' => 'Marca Obtenida.',
            'status' => 200
        ]);
    }

    function updateBrand(BrandRequest $request, Brand $item)
    {
        $item->update([
            'name' => $request->name
        ]);

        return BrandResource::make($item)->additional([
            'message' => 'Marca Actualizada.',
            'status' => 200
        ]);
    }

    function deleteBrand(Brand $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return BrandResource::make($item)->additional(([
                'message' => 'Marca Eliminada.',
                'status' => 200
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Marca No Eliminada.',
                'status' => 500,
            ], 500);
        }
    }
}
