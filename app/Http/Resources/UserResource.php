<?php

namespace App\Http\Resources;

use App\Models\Role;
use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    use GeneratesUrls;

    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'image_url' => $this->getFullUrl($this->image),
        ]);
    }
}
