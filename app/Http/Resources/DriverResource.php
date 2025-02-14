<?php

namespace App\Http\Resources;

use App\Traits\GeneratesUrls;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DriverResource extends JsonResource
{
    use GeneratesUrls;

    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            // Genera la URL completa para la imagen del conductor
            'image_url' => $this->getFullUrl($this->image),

            // Genera la URL completa para el archivo del conductor
            'file_driver_url' => $this->getFullUrl($this->file_driver),

            // Carga la relación "cars" si está disponible
            'cars' => CarResource::collection($this->whenLoaded('cars')),

            // Carga la relación "latestLicense" si está disponible
            'latest_license' => $this->whenLoaded('latestLicense', function () {
                return [
                    'number' => $this->latestLicense->number,
                    'class' => $this->latestLicense->class,
                    'category' => $this->latestLicense->category,
                    'issue_date' => $this->latestLicense->issue_date,
                    'renewal_date' => $this->latestLicense->renewal_date,
                    'file' => $this->latestLicense->file ? $this->getFullUrl($this->latestLicense->file) : null, // URL completa para el archivo de la licencia
                    'driver_id' => $this->latestLicense->driver_id,
                    'is_valid' => $this->latestLicense->renewal_date >= now()->toDateString(),
                ];
            }),
        ]);
    }
}
