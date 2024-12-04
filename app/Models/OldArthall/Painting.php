<?php
#TODO удалить после переезда на новый Arthall

namespace App\Models\OldArthall;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Painting extends Model
{

    use SoftDeletes;


    protected $connection= 'mysql_old_arthall';

    protected $table = 'paintings';

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
