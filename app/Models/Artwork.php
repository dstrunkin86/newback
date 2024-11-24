<?php

namespace App\Models;

use App\Filters\Filter;
use App\Traits\HasImages;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artwork extends Model
{
    use SoftDeletes, HasImages;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'status',
        'title',
        'description',
        'year',
        'location',
        'artist_id',
        'width',
        'height',
        'depth',
        'weight',
        'in_sale',
        'price',


    ];

    protected $casts = [
        'title' => 'object',
        'description' => 'object',
    ];

    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        return $filter->apply($builder);
    }

    /**
     * Get the artist for the artwork.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * List of the artwork tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * List of the artwork compilations.
     */
    public function compilations(): BelongsToMany
    {
        return $this->belongsToMany(Compilation::class);
    }
}
