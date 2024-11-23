<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExampleRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\ExampleResource;
use App\Models\Example;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExampleController extends Controller
{
    function getExamples(ListRequest $request)
    {
        $items = Example::included()->where('name', 'like', '%' . $request->search . '%')->orderBy('id', $request->sort);
        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return ExampleResource::collection($items);
    }

    function registerExample(ExampleRequest $request)
    {
        $item = Example::create([
            'name' => $request->name,
        ]);

        return ExampleResource::make($item)->additional([
            'message' => 'Modelo Registrado.',
        ]);
    }

    function getExample($item)
    {
        $item = Example::included()->find($item);
        return ExampleResource::make($item);
    }

    function updateExample(ExampleRequest $request, Example $item)
    {
        $item->update([
            'name' => $request->name
        ]);

        return ExampleResource::make($item)->additional([
            'message' => 'Modelo Actualizado.'
        ]);
    }

    function deleteExample(Example $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return ExampleResource::make($item);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Modelo No Borrada.',
                'status' => 500,
            ], 500);
        }
    }
}
