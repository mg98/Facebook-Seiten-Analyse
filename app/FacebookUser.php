<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookUser extends Model
{
    protected $table = 'facebook_users';

    protected $fillable = [
        'post_id',
        'facebook_id',
        'name'
    ];
}
