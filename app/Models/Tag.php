<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filters\Filter;

class Tag extends Model
{
    use SoftDeletes;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'type',
        'title',

    ];

    protected $casts = [
        'title' => 'object',
    ];


    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        return $filter->apply($builder);
    }


    /**
     * List of the tagged artworks.
     */
    public function artworks(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class);
    }

    /**
     * List of the tagged artists.
     */
    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class);
    }
}
