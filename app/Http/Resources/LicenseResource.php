<?php

namespace App\Http\Resources;

use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenseResource extends JsonResource
{
    use GeneratesUrls;

    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'status' => now()->lessThanOrEqualTo($this->renewal_date),
            'file_url' => $this->when($this->file, $this->getFullUrl($this->file)),
        ]);
    }
}
