<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->getTranslations('title'),
            'image'     => $this->getFirstMediaUrl('banner_image', 'main'),
            'thumbnail' => $this->getFirstMediaUrl('banner_image', 'thumbnail'),
            'original'  => $this->getFirstMediaUrl('banner_image'),
        ];
    }
}
