<?php

namespace App\Models\Synergy;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\URL;

class Image extends Model
{
    protected $connection= 'mysql_synergy';

    protected $fillable = [
        'url',
        'imageable_id',
        'imageable_type'
    ];

    protected $hidden = [
        'imageable_id',
        'imageable_type'
    ];

    public $timestamps = false;

    /**
     * @return MorphTo
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}
