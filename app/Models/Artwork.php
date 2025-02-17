<?php

namespace App\Models;

use App\Filters\Filter;
use App\Observers\ArtworkObserver;
use App\Traits\HasImages;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ArtworkObserver::class])]
class Artwork extends Model
{
    use SoftDeletes, HasImages;

    protected $hidden = ['pivot'];

    protected $fillable = [
        'status',
        'status_comment',
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
        'tech_info'


    ];

    protected $casts = [
        'title' => 'object',
        'description' => 'object',
        'location' => 'object',
        'tech_info' => 'object'
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

    /**
     * List of the artwork orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Список похожих картин.
     */
    public function getSimilarPaintingsAttribute()
    {
        return Artwork::query()
            ->with('artist')
            ->where('id','<>',$this->id)
            ->where('status','accepted')
            ->inRandomOrder()
            ->limit(5)
            ->get();
    }
}
