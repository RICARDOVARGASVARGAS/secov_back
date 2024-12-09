<?php

namespace App\Http\Resources;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            // 'storage' => $this->image ? 'storage/' . $this->image : null,
            'brand' => BrandResource::make($this->whenLoaded('brand')),
            'typeCar' => TypeCarResource::make($this->whenLoaded('typeCar')),
            'group' => GroupResource::make($this->whenLoaded('group')),
            'year' => YearResource::make($this->whenLoaded('year')),
            'color' => ColorResource::make($this->whenLoaded('color')),
            'example' => ExampleResource::make($this->whenLoaded('example')),
            'driver' => DriverResource::make($this->whenLoaded('driver')),
            'example' => ExampleResource::make($this->whenLoaded('example')),
        ]);
    }
}
