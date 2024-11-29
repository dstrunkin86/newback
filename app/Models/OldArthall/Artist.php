<?php

namespace App\Models\OldArthall;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use SoftDeletes;

    protected $connection= 'mysql_old_arthall';

    protected $table = 'artists';

    public function paintings()
    {
        return $this->hasMany(Painting::class)->orderBy('id','desc');
    }
}
