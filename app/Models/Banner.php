<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Banner extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = ['title'];

    public array $translatable = ['title'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner_image')
            ->singleFile()
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('main')
            ->fit(Fit::Max, 1920, 1080)
            ->quality(85)
            ->format('jpg')
            ->performOnCollections('banner_image')
            ->nonQueued(); // Use ->queued() for production with queue workers

        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 300, 300)
            ->quality(80)
            ->format('jpg')
            ->performOnCollections('banner_image')
            ->nonQueued(); // Use ->queued() for production with queue workers
    }
}
