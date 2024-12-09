<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\ListRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    function getGroups(ListRequest $request)
    {
        $query = Group::included()
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderBy('id', $request->sort);

        if ($request->perPage === 'all') {
            $items = $query->get();
        } else {
            $items = $query->paginate(
                $request->perPage,
                ['*'],
                'page',
                $request->page
            );
        }

        return GroupResource::collection($items);
    }

    function registerGroup(GroupRequest $request)
    {
        $item = Group::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return GroupResource::make($item)->additional([
            'message' => 'Asociación Registrada.',
        ]);
    }

    function getGroup($item)
    {
        $item = Group::included()->find($item);
        return GroupResource::make($item);
    }

    function updateGroup(GroupRequest $request, Group $item)
    {
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return GroupResource::make($item)->additional([
            'message' => 'Asociación Actualizada.'
        ]);
    }

    function deleteGroup(Group $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return GroupResource::make($item);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Asociación No Borrada.',
                'status' => 500,
            ], 500);
        }
    }
}
