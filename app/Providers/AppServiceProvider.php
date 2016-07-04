<?php

namespace App\Providers;

use App\Providers\FacebookApiServiceProvider;
use Illuminate\Support\ServiceProvider;
use Validator;
use App\FacebookPage;
use App\PostMark;

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
            $fb = FacebookApiServiceProvider::get();
            try {
                $response = $fb->get($value);
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

        // Überprüft ob die Seite nicht schon vorher eingetragen wurde
        Validator::extend('pageNotRegistered', function($attribute, $value, $parameters, $validator) {
            $fb = FacebookApiServiceProvider::get();
            try {
                $response = $fb->get($value);
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

        // Überprüft ob die Seite nicht schon vorher an diesem Post markiert wurde
        Validator::extend('pageNotMarked', function($attribute, $value, $parameters, $validator) {
            $fb = FacebookApiServiceProvider::get();
            try {
                $response = $fb->get($value);
                $pageNode = $response->getGraphPage();
                $facebookId = $pageNode->all()['id'];
                $postId = $parameters[0];
                return !PostMark::where('facebook_id', $facebookId)->where('post_id', $postId)->exists();
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
