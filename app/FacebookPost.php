<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    protected $table = 'posts';

    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'facebook_id',
        'text',
        'published_at'
    ];

    public function getCollectedUsers() {
        return FacebookUser::where('post_id', $this->id);
    }

}
