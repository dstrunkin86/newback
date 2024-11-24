<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'language',
        'text',
        'description',
        'keywords',
        'image',
        'publication_date',
        'is_published',
        'link',

    ];
}
