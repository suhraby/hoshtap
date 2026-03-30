<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Contact extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug',
        'icon',
        'value',
        'locale_value',
        'is_active',
    ];

    public array $translatable = ['locale_value'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
