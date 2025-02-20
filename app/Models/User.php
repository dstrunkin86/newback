<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Filters\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    protected $appends = ['role', 'stage'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fcm_token',
        'lang',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeFilter(Builder $builder, Filter $filter): Builder
    {
        return $filter->apply($builder);
    }

    public function GetRoleAttribute() {
        $roles = $this->getRoleNames()->toArray();
        return $roles;
    }

    /**
     * Joined artist.
     */
    public function artist(): HasOne
    {
        return $this->hasOne(Artist::class);
    }

    /**
     * @return int
     */
    public function getStageAttribute(): int
    {
        if (is_null($this->email) && is_null($this->passport)){
            return 1;
        }

        if (is_null($this->artist()->first())){
            return 2;
        }

        $artist = $this->artist()->first();

        if (!$artist->creative_concept){
            return 3;
        }

        if (!count($artist->artworks()->get())){
            return 4;
        }

        return 5;
    }

}
