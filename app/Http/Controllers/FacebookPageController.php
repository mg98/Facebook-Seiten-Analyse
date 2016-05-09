<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Facebook\Facebook;
use \App\FacebookPage;
use \App\FacebookPost;
use Illuminate\Support\Facades\Redirect;

class FacebookPageController extends Controller
{

    public $fb = null;

    /**
     * Facebook Graph API ansprechen
     */
    public function __construct() {
        $this->fb = new Facebook([
            'app_id' => env('FB_APPID'),
            'app_secret' => env('FB_SECRET'),
            'default_graph_version' => 'v2.5',
            'default_access_token' => env('FB_ACCESSTOKEN')
        ]);
    }

    /**
     * Neue Facebook Seite erstellen
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request) {
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
        return view('fbpage/new');
    }

    /**
     * Ansichtsseite
     *
     * @param string $fbpage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($fbpage) {
        $fbpage = $this->getFacebookPage($fbpage);
        return $fbpage ? view('fbpage/show', compact('fbpage')) : Redirect::to('404');
    }

    /**
     * Läd Posts einer Facebook-Seite nach
     *
     * @param string $fbpage
     * @return mixed
     */
    public function getPosts($fbpage) {
        $fbpage = $this->getFacebookPage($fbpage);

        if ($fbpage == false) {
            return Redirect::to('404');
        }

        $posts = $this->fb->get($fbpage->facebook_id . '/posts')->getGraphEdge();
        if (!FacebookPost::where('page_id', $fbpage->id)->exists()) {
            foreach ($posts->all() as $post) {
                $post = $post->all();
                $newPost = new FacebookPost;
                $newPost->page_id = $fbpage->id;
                $newPost->facebook_id = $post['id'];
                $newPost->text = substr($post['message'], 0, 50);
                $newPost->published_at = $post['created_time'];
                $newPost->save();
            }
        }


    }

    /**
     * Findet anhand des Seitennamens die Seite oder leitet auf 404-Seite um
     *
     * @param string $fbpage
     * @return FacebookPage|bool
     */
    private function getFacebookPage($fbpage) {
        $fbpage = FacebookPage::where('name', niceDecode($fbpage));

        if (!$fbpage->exists()) {
            return false;
        } else {
            return $fbpage->first();
        }
    }

}
