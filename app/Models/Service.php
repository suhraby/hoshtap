<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'description', 'icon', 'sort_order'];

    public array $translatable = ['title', 'description'];
}
