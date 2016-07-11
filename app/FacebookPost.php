<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class FacebookPost extends Model
{
    protected $table = 'fb_posts';

    public $timestamps = false;

    protected $fillable = [
        'facebook_page_id',
        'facebook_id',
        'text',
        'published_at'
    ];

    /**
     * Holt Facebook User die mit diesem Post zusammenhängen
     *
     * @return HasMany
     */
    public function users() {
        return $this->hasMany('App\FacebookUser');
    }

    /**
     * Holt markierte Seiten dieses Posts
     *
     * @return HasMany
     */
    public function postMarks() {
        return $this->hasMany('App\PostMark');
    }

}
