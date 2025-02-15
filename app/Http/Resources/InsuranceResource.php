<?php

namespace App\Http\Resources;

use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceResource extends JsonResource
{
    use GeneratesUrls;
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'status' => now()->lessThanOrEqualTo($this->expiration_date),
            'file_insurance_url' => $this->when($this->file_insurance, $this->getFullUrl($this->file_insurance)),
        ]);
    }
}
