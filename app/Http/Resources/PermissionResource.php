<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ]);
    }
}
