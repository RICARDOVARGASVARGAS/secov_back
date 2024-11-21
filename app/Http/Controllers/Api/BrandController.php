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
    function getBrands(ListRequest $request)
    {
        $items = Brand::included()->where('name', 'like', '%' . $request->search . '%')->orderBy('id', 'desc');
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return BrandResource::collection($items);
    }

    function registerBrand(BrandRequest $request)
    {
        $item = Brand::create([
            'name' => $request->name,
        ]);

        return BrandResource::make($item)->additional([
            'message' => 'Marca Registrada.',
        ]);
    }

    function getBrand($item)
    {
        $item = Brand::included()->find($item);
        return BrandResource::make($item);
    }

    function updateBrand(BrandRequest $request, Brand $item)
    {
        $item->update([
            'name' => $request->name
        ]);

        return BrandResource::make($item)->additional([
            'message' => 'Marca Actualizada.'
        ]);
    }

    function deleteBrand(Brand $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return BrandResource::make($item);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
