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
            'latest_insurance' => $this->whenLoaded('latestInsurance', function () {
                return [
                    'number_insurance' => $this->latestInsurance->number_insurance,
                    'issue_date' => $this->latestInsurance->issue_date,
                    'expiration_date' => $this->latestInsurance->expiration_date,
                    'file_insurance' => $this->latestInsurance->file_insurance,
                    'is_valid' => $this->latestInsurance->expiration_date >= now()->toDateString(),
                ];
            }),
            'latest_permit' => $this->whenLoaded('latestPermit', function () {
                return [
                    'issue_date' => $this->latestPermit->issue_date,
                    'expiration_date' => $this->latestPermit->expiration_date,
                    'file_permit' => $this->latestPermit->file_permit,
                    'is_valid' => $this->latestPermit->expiration_date >= now()->toDateString(),
                ];
            }),
        ]);
    }
}
