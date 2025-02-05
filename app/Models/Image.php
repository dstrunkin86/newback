<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'url',
        'preview_url',
        'pre',
        'priority',
        'width',
        'height'
    ];

    public $timestamps = false;
}
