<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use App\Http\Requests;
use \Facebook\Facebook;
use App\FacebookPage;
use App\FacebookPost;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination;
use \Illuminate\View\View;

class FacebookPageController extends Controller
{

    /**
     * @var Facebook $fb
     */
    private $fb = null;

    /**
     * Facebook Graph API ansprechen
     */
    public function __construct() {
        $this->fb = \App\Providers\FacebookApiServiceProvider::get();
    }

    /**
     * Neue Facebook Seite erstellen
     *
     * @param Request $request
     * @return View
     */
    public function add(Request $request) {
        $this->validate($request, [
            'page' => 'isFacebookPage'
        ]);
        $this->validate($request, [
            'page' => 'pageNotRegistered'
        ]);

        $response = $this->fb->get($request->get('page'));
        $pageNode = $response->getGraphPage()->all();

        $newPage = new FacebookPage;
        $newPage->name = $pageNode['name'];
        $newPage->facebook_id = $pageNode['id'];
        $newPage->save();

        $request->session()->flash('success', 'Die Facebook Seite "'.$pageNode['name'].'" wurde erfolgreich hinzugefügt!');
        return Redirect::back();
    }

    /**
     * Ansichtsseite
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request) {
        $fbpage = $request->get('fbpage');

        $posts = $fbpage->posts()->paginate(20);

        return view('fbpage/show', compact('fbpage', 'posts'));
    }

    /**
     * Lädt Posts einer Facebook-Seite nach
     *
     * @param Request $request
     * @return mixed
     */
    public function getPosts(Request $request) {
        $fbpage = $request->get('fbpage');
        // Posts anfordern
        if (!FacebookPost::where('facebook_page_id', $fbpage->id)->exists()) {
            // Wenn es zu dieser Facebook Seite noch keine gibt, hol alle
            $posts = $this->fb->get($fbpage->facebook_id . '/posts?limit=100')->getGraphEdge();
        } else {
            // Sonst hol nur die Posts seit dem letzten Eintrag
            $latestPost = FacebookPost::where('facebook_page_id', $fbpage->id)->orderBy('published_at', 'desc')->first();
            $lastDay = date('Y-m-d', strtotime($latestPost['published_at']));
            $posts = $this->fb->get($fbpage->facebook_id . '/posts?limit=100&since=' . $lastDay)->getGraphEdge();
        }

        // Posts in der Datenbank abspeichern
        foreach ($posts->all() as $post) {
            $post = $post->all();
            // Wenn bereits eingetragen, überspringen
            if (FacebookPost::where('facebook_id', $post['id'])->exists()) {
                continue;
            }
            $newPost = new FacebookPost;
            $newPost->facebook_page_id = $fbpage->id;
            $newPost->facebook_id = $post['id'];
            $text = array_key_exists('message', $post) ? $post['message'] : $post['story'];
            $newPost->text = substr($text, 0, 50);
            $newPost->published_at = $post['created_time'];
            $newPost->save();
        }

        return Redirect::back();
    }

    public function remove(Request $request) {
        $fbpage = $request->get('fbpage');
        dd($fbpage);
        return Redirect::back();
    }

    /**
     * Holt einen Access Token der erst in 2 Monaten ausläuft
     *
     * @return string
     */
    public function getAccessToken() {
        //$getLlat = $this->fb->get('/oauth/access_token?grant_type=fb_exchange_token&client_id='.env('FB_APPID').'&client_secret='.env('FB_SECRET').'&fb_exchange_token='.env('FB_ACCESSTOKEN'));
        //$llat = $getLlat->getDecodedBody()['access_token'];
        //return $llat;

        $accessToken = $this->fb->get('/oauth/access_token?client_id='.env('FB_APPID').'&client_secret='.env('FB_SECRET').'&grant_type=fb_exchange_token&fb_exchange_token='.env('FB_ACCESSTOKEN'));
        return $accessToken;
    }

}
