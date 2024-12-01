<?php

namespace App\Models\Synergy;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Filters\Filter;
use App\Filters\HasFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $connection= 'mysql_synergy';

    //protected $appends = ['artistWorks', 'avatar', 'artistCv'];
    //protected $appends = ['avatar'];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'email_verified',
        'password',
        'exhibitions_text',
        'awards',
        'sales',
        'about_me',
        'description',
        'office_hours',
        'status',
        'social',
        'public_contact',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'avatar'
    ];

    protected $with = [
        //'avatar',
        //'documentFile',
        'artistWorks',
        'artistCv'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'social' => 'array',
        'public_contact' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    // public function exhibitions():HasMany
    // {
    //     return $this->hasMany(Exhibition::class);
    // }

    /**
     * @return HasMany
     */
    public function artistWorks(): HasMany
    {
        return $this->hasMany(ArtistWorks::class);
    }

    // /**
    //  * @return MorphOne
    //  */
    // public function avatar():MorphOne
    // {
    //     return $this->morphOne(Image::class, 'imageable')->latest('id');
    // }

    public function getAvatarAttribute()
    {
        $result = Image::where('imageable_id',$this->id)->where('imageable_type','App\Models\User')->get()->last();

        return ($result) ? $result->toArray() : null;
    }

    // /**
    //  * @return HasMany
    //  */
    // public function moderationLogs():HasMany
    // {
    //     return $this->hasMany(ModerationLogs::class);
    // }

    // /**
    //  * @return MorphMany
    //  */
    // public function files():MorphMany
    // {
    //     return $this->morphMany(File::class, 'fileable');
    // }

    // public function documentFile()
    // {
    //     return $this->morphOne(File::class, 'fileable')->latest('id');
    // }

    // /**
    //  * @return HasMany
    //  */
    // public function artistRatings(): HasMany
    // {
    //     return $this->hasMany(ArtistRating::class, 'artist_id', 'id');
    // }

    // /**
    //  * @return HasOne
    //  */
    // public function myRating(): HasOne
    // {
    //     return $this->hasOne(ArtistRating::class, 'artist_id', 'id');
    // }

    // public function getAllPermissionsAttribute()
    // {
    //     $permissions = [];
    //     foreach (Permission::all() as $permission) {
    //         if (Auth::user()->can($permission->name)) {
    //             $permissions[] = $permission->name;
    //         }
    //     }
    //     return $permissions;
    // }

    /**
     * @return HasOne
     */
    public function artistCv(): HasOne
    {
        return $this->hasOne(ArtistCv::class);
    }

    /**
     * @return HasOne
     */
    public function arthallRequests(): HasOne
    {
        return $this->hasOne(ArthallRequest::class);
    }

    // /**
    //  * @return HasMany
    //  */
    // public function userItems(): HasMany
    // {
    //     return $this->hasMany(TrackItemUser::class);
    // }
}
