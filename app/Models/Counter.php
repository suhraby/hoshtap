<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Counter extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'number',
        'symbol',
        'sort_order',
    ];

    public array $translatable = ['title', 'description'];
}
