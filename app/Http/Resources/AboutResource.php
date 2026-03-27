<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->getTranslations('title'),
            'market_title' => $this->getTranslations('market_title'),
            'body'         => $this->getTranslations('body'),
            'image'        => $this->getFirstMediaUrl('about_image'),
            'market_image' => $this->getFirstMediaUrl('market_image'),
        ];
    }
}
