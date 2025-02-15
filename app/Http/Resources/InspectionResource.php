<?php

namespace App\Http\Resources;

use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResource extends JsonResource
{
    use GeneratesUrls;

    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'status' => now()->lessThanOrEqualTo($this->expiration_date),
            'file_inspection_url' => $this->when($this->file_inspection, $this->getFullUrl($this->file_inspection)),
        ]);
    }
}
