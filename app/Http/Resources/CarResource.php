<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    use GeneratesUrls;

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
            'file_car_url' => $this->when($this->file_car, $this->getFullUrl($this->file_car)),
            'latest_inspection' => $this->whenLoaded('latestInspection', function () {
                // return [
                //     'issue_date' => $this->latestInspection->issue_date,
                //     'expiration_date' => $this->latestInspection->expiration_date,
                //     'file_inspection' => "https://www.instagram.com/",
                //     'is_valid' => $this->latestInspection->expiration_date >= now()->toDateString(),
                // ];
                return InspectionResource::make($this->latestInspection);
            }),
            'latest_insurance' => $this->whenLoaded('latestInsurance', function () {
                // return [
                //     'number_insurance' => $this->latestInsurance->number_insurance,
                //     'issue_date' => $this->latestInsurance->issue_date,
                //     'expiration_date' => $this->latestInsurance->expiration_date,
                //     'file_insurance' => $this->latestInsurance->file_insurance,
                //     'is_valid' => $this->latestInsurance->expiration_date >= now()->toDateString(),
                // ];

                return InsuranceResource::make($this->latestInsurance);
            }),
            'latest_permit' => $this->whenLoaded('latestPermit', function () {
                // return [
                //     'issue_date' => $this->latestPermit->issue_date,
                //     'expiration_date' => $this->latestPermit->expiration_date,
                //     'file_permit' => $this->latestPermit->file_permit,
                //     'is_valid' => $this->latestPermit->expiration_date >= now()->toDateString(),
                // ];

                return PermitResource::make($this->latestPermit);
            }),


        ]);
    }
}
