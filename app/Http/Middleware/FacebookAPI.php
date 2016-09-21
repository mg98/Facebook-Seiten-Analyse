<?php

namespace App\Http\Middleware;

use Closure;
use \Facebook\Facebook;

class FacebookAPI
{

    private static $fb;

    /**
     * Initialisiert die Facebook API Schnittstelle
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        self::$fb = new Facebook([
            'app_id' => $user->fb_app_id,
            'app_secret' => $user->fb_app_secret,
            'default_graph_version' => 'v2.5',
            'default_access_token' => $user->fb_accesstoken
        ]);

        return $next($request);
    }

    /**
     * Gibt die FB API zurück
     *
     * @return Facebook
     */
    public static function get() {
        return self::$fb;
    }

}
