<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property string $role
 * @property string $email
 * @property string $password
 * @property-read \App\UserProfile $profile
 * @mixin \Eloquent
 */

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
    public $timestamps = false;

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }
}
