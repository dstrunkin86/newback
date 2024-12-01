<?php

namespace App\Models\Synergy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtistCv extends Model
{

    protected $connection= 'mysql_synergy';

    protected $fillable = [
        'user_id',
        'fio',
        'city',
        'email',
        'phone',
        'educations',
        'qualifications',
        'exhibition_projects',
        'publications',
        'concept_creativity',
        'style_work',
        'style_work_other',
        'themes_works',
        'themes_works_other',
    ];

    protected $casts = [
        'educations' => 'array',
        'qualifications' => 'array',
        'exhibition_projects' => 'array',
        'publications' => 'array',
        'style_work' => 'array',
        'themes_works' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
