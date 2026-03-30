<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $value = $this->value;

        if (!$value) {
            $value = $this->getTranslations('locale_value');
        }

        return [
            'id'           => $this->id,
            'slug'         => $this->slug,
            'icon'         => $this->icon,
            'value'        => $value,
            'locale_value' => $this->getTranslations('locale_value'),
            'is_active'    => $this->is_active,
        ];
    }
}
