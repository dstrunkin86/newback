<?php

namespace App\Models\Synergy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArthallRequest extends Model
{
    use HasFactory;

    protected $connection= 'mysql_synergy';

    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
