<?php

namespace App\Models;

use App\Filters\Filter;
use App\Traits\HasImages;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use SoftDeletes, HasImages;

    protected $fillable = [
        'source',
        'status',
        'status_comment',
        'fio',
        'alias',
        'email',
        'vk',
        'telegram',
        'phone',
        'city',
        'country',
        'creative_concept',
        'education',
        'qualification',
        'exhibitions',
        'publications'

    ];

    protected $casts = [
        'fio' => 'object'
    ];

    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        return $filter->apply($builder);
    }

    /**
     * Get the artworks for the artist.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }

    /**
     * Joined user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
