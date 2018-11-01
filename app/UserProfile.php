<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserProfile
 *
 * @property string $phone
 * @property string $first_name
 * @property string $last_name
 * @mixin \Eloquent
 */

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    public $timestamps = false;
    protected $fillable = [
        'phone', 'first_name', 'last_name',
    ];
}
