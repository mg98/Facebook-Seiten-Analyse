<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    protected $table = 'posts';

    public $timestamps = false;

    protected $fillable = [
        'facebook_page_id',
        'facebook_id',
        'text',
        'published_at'
    ];

    public function users() {
        return $this->hasMany('App\FacebookUser');
    }

}
