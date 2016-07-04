<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMark extends Model
{
    protected $table = 'fb_posts_marks';

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'name',
        'facebook_id'
    ];
}
