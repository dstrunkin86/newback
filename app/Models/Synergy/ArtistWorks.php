<?php

namespace App\Models\Synergy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArtistWorks extends Model
{

    protected $connection= 'mysql_synergy';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'year',
    ];

    protected $appends = [
        'image'
    ];


    // public function image():MorphOne
    // {


    //     return $this->morphOne(Image::class, 'imageable')->latest('id');
    // }


    public function getImageAttribute()
    {
        $result = Image::where('imageable_id',$this->id)->where('imageable_type','App\Models\ArtistWorks')->get()->last();

        return ($result) ? $result->toArray() : null;

    }
    /**
     * @return BelongsTo
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // /**
    //  * @return HasMany
    //  */
    // public function ratings():HasMany
    // {
    //     return $this->hasMany(ArtistWorkRating::class);
    // }

    // /**
    //  * @return HasMany
    //  */
    // public function arthallRatings():HasMany
    // {
    //     return $this->hasMany(ArtistWorkArthallRating::class);
    // }
}
