<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class About extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $table = 'about';

    protected $fillable = ['title', 'body'];

    public array $translatable = ['title', 'body'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('about_image')
            ->singleFile()
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }
}
