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

    /**
     * Holt alle Posts dieser Seite aus der Datenbank
     *
     * @return mixed
     */
    public function getPosts() {
        return FacebookPost::where('page_id', $this->id)->orderBy('published_at', 'desc');
    }

}
