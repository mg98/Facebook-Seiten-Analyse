<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @return HasMany
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
        $users = [];
        foreach ($this->posts()->get()->all() as $post) {
            $users = array_merge($users, $post->users()->get()->all());
        }
        return collect($users);
    }

}
