<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    protected $table = 'facebookpages';

    protected $fillable = [
        'name',
        'facebook_id'
    ];

}
