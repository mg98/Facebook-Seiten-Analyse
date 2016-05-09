<?php

namespace App\Providers;

use App\Http\Controllers\FacebookPageController;
use Illuminate\Support\ServiceProvider;
use Validator;
use App\FacebookPage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Facebook page validator
        Validator::extend('isFacebookPage', function($attribute, $value, $parameters, $validator) {
            $fbc = new FacebookPageController;
            try {
                $response = $fbc->fb->get($value);
                $pageNode = $response->getGraphPage();
                return boolval($pageNode);
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                return false;
            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                die('Facebook SDK returned an error: ' . $e->getMessage());
            }
        });

        // Validator for already registered facebook pages
        Validator::extend('pageNotRegistered', function($attribute, $value, $parameters, $validator) {
            $fbc = new FacebookPageController;
            try {
                $response = $fbc->fb->get($value);
                $pageNode = $response->getGraphPage();
                $facebookId = $pageNode->all()['id'];
                return !FacebookPage::where('facebook_id', $facebookId)->exists();
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                return false;
            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                die('Facebook SDK returned an error: ' . $e->getMessage());
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
