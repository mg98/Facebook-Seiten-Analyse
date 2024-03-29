<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookUser extends Model
{
    protected $table = 'fb_users';

    protected $fillable = [
        'facebook_post_id',
        'facebook_id',
        'name',
        'count'
    ];

}
