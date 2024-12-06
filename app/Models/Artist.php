<?php

namespace App\Models;

use App\Filters\Filter;
use App\Observers\ArtistObserver;
use App\Traits\HasImages;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ArtistObserver::class])]
class Artist extends Model
{
    use SoftDeletes, HasImages;

    protected $fillable = [
        'source',
        'status',
        'status_comment',
        'fio',
        'url',
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
        'publications',
        'tech_info',
        'external_id',
    ];

    protected $casts = [
        'fio' => 'object',
        'creative_concept' => 'object',
        'education' => 'object',
        'qualification' => 'object',
        'exhibitions' => 'object',
        'publications' => 'object',
        'tech_info' => 'object',
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
     * List of the artwork tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Joined user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
