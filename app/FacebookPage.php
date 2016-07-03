<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class FacebookPage extends Model
{
    protected $table = 'fb_pages';

    protected $fillable = [
        'name',
        'facebook_id'
    ];

    /**
     * Holt alle Posts dieser Seite aus der Datenbank
     *
     * @return mixed
     */
    public function posts() {
        return $this->hasMany('App\FacebookPost')->orderBy('published_at', 'desc');
    }

    /**
     * Holt sich alle gesammelten Nutzer die dieser
     * Seite zugehören
     *
     * @return Collection
     */
    public function users() {
        $this->load('posts.users');
        $users = $this->posts->lists('users');
        $collection = new Collection;
        foreach ($users as $postUser) {
            $collection = $collection->merge($postUser);
        }
        return $collection;
    }

}
