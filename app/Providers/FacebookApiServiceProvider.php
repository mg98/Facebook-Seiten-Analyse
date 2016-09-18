<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Facebook\Facebook;
use Illuminate\Support\Facades\Auth;

class FacebookApiServiceProvider extends ServiceProvider
{

    /**
     * @var Facebook|null
     */
    private static $fb = null;

    /**
     * Initialisiert die Schnittstelle zur Facebook Graph API
     *
     * @return void
     */
    public function boot()
    {
        self::$fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => 'v2.5',
            'default_access_token' => env('FB_ACCESSTOKEN')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    /**
     * Gibt die Schnittstelle zur Facebook API zurück
     *
     * @return Facebook
     */
    public static function get() {
        return self::$fb;
    }
}
