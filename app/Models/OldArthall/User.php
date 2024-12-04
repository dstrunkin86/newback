<?php

namespace App\Models\OldArthall;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property mixed lang
 * @property mixed fcm_token
 */
class User extends Authenticatable
{

    protected $connection= 'mysql_old_arthall';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'lang', 'fcm_token', 'device_name'
    ];



}
