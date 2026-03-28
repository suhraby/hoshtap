<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'icon'        => $this->icon,
            'title'       => $this->getTranslations('title'),
            'sort_order'  => $this->sort_order,
            'description' => $this->getTranslations('description'),
        ];
    }
}
