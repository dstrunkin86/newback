<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compilation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'priority',
        'title',
        'image',
        'description',
        'is_published'

    ];

    protected $casts = [
        'title' => 'object',
        'description' => 'object',
    ];

    /**
     * List of the compilation artworks.
     */
    public function artworks(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class);
    }
}
