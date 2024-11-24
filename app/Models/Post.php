<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Filters\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'language',
        'title',
        'text',
        'image',
        'description',
        'keywords',
        'publication_date',
        'is_published',
        'link',

    ];

    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        return $filter->apply($builder);
    }
}
