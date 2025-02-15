<?php

namespace App\Http\Resources;

use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermitResource extends JsonResource
{
    use GeneratesUrls;
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'status' => now()->lessThanOrEqualTo($this->expiration_date),
            'file_permit_url' => $this->when($this->file_permit, $this->getFullUrl($this->file_permit)),
        ]);
    }
}
