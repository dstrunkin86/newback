<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * List of the tagged artworks.
     */
    public function artworks(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class);
    }
}
