<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            // 'storage' => $this->image ? 'storage/' . $this->image : null,
            // 'brand' => BrandResource::make($this->whenLoaded('brand')),
            // 'latest_license' => $this->whenLoaded('latestLicense', function () {
            //     return [
            //         'number' => $this->latestLicense->number,
            //         'class' => $this->latestLicense->class,
            //         'category' => $this->latestLicense->category,
            //         'issue_date' => $this->latestLicense->issue_date,
            //         'renewal_date' => $this->latestLicense->renewal_date,
            //         'file' => $this->latestLicense->file,
            //         'driver_id' => $this->latestLicense->driver_id,
            //         'is_valid' => $this->latestLicense->renewal_date >= now()->toDateString(),
            //     ];
            // }),
        ]);
    }
}
