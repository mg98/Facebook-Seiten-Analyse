<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use App\Http\Requests;
use \Facebook\Facebook;
use App\FacebookPage;
use App\FacebookPost;
use App\FacebookUser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination;
use \Illuminate\View\View;

class FacebookPageController extends Controller
{

    /**
     * @var Facebook $fb
     */
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
     * @return View
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

    /**
     * Ergebnisseite einer Seite/ Auswertung der Analyse
     *
     * @param $fbpage
     * @return View
     */
    public function showResults(Request $request) {
        $page = isset($_GET['page']) && intval($_GET['page']) ? $_GET['page'] : 1;

        if (Cache::has('result_page_'.$page)) {
            return Cache::get('result_page_'.$page);
        }

        $fbpage = $request->get('fbpage');
        $fbusers = $fbpage->users();
        $users = $fbusers->sortByDesc('count')->forPage($page, 15);
        $pagination = new Pagination\LengthAwarePaginator($fbusers->all(), $fbusers->count(), 15, $page);
        $pagination->setPath($request->getPathInfo());

        $result_page = view('fbpage/results', compact('fbpage', 'users', 'pagination'))->render();
        Cache::put('result_page_'.$page, $result_page, 10);

        return $result_page;
    }

    /**
     * Startet Nutzeranalyse (Aufruf über AJAX)
     *
     * @param Request $request
     * @return string
     */
    public function startAnalysis(Request $request) {
        ini_set('max_execution_time', 3600 * 24);

        $fbpage = $request->get('fbpage');

        $fbpage->analyzing = true;
        $fbpage->save();

        foreach ($fbpage->posts()->get() as $post) {
            try {
                // Datum des zuletzt hinzugefügten Usereintrags ziehen
                $lastEntry = $post->users()->first();
                $lastAnalysis = strtotime($lastEntry['created_at']) + 1;
                // Likes und Kommentare ziehen
                $likes = $this->fb->get($post->facebook_id . '/likes?limit=' . env('FB_LIMIT') . '&since=' . $lastAnalysis)->getGraphEdge()->all();
                $comments = $this->fb->get($post->facebook_id . '/comments?limit=' . env('FB_LIMIT') . '&since=' . $lastAnalysis)->getGraphEdge()->all();
                // Durch alle Likes UND Kommentare iterieren
                foreach (array_merge($likes, $comments) as $data) {
                    // Überprüfe ob es sich gerade um ein Kommentar handelt
                    $data = $data->all();
                    if (array_key_exists('from', $data)) {
                        $data = $data['from']->all();
                    }
                    // Überprüfe ob der User schon bekannt ist (bei dieser FB-Seite)
                    $registeredUser = FacebookUser::where('facebook_id', $data['id']);
                    if ($registeredUser->exists()) {
                        $newUser = $registeredUser->first();
                        $newUser->count++;
                    } else {
                        // Erstelle neuen Nutzer Eintrag
                        $newUser = new FacebookUser;
                        $newUser->facebook_post_id = $post['id'];
                        $newUser->facebook_id = $data['id'];
                        $newUser->name = $data['name'];
                    }
                    $newUser->save();
                }
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                if ($e->getCode() == 100) {
                    continue;
                } else {
                    $fbpage->analyzing = false;
                    $fbpage->save();
                    exit($e->getMessage());
                }
            }
        }

        // Alten Cache der Ergebnisseite löschen und neuen erstellen
        Cache::flush();
        $fbusers = $fbpage->users();
        $result_page_path = substr($request->getPathInfo(), 0, nth_strpos($request->getPathInfo(), '/', 3));
        for ($page = 1; $page < $fbusers->count(); $page++) {
            $users = $fbusers->sortByDesc('count')->forPage($page, 15);
            $pagination = new Pagination\LengthAwarePaginator($fbusers->all(), $fbusers->count(), 15, $page);
            $pagination->setPath($result_page_path);
            $result_page = view('fbpage/results', compact('fbpage', 'users', 'pagination'))->render();
            Cache::put('result_page_'.$page, $result_page, 10);
        }

        $fbpage->analyzing = false;
        $fbpage->save();

        exit('success');
    }

    public function stopAnalysis(Request $request) {
        $fbpage = $request->get('fbpage');
        $fbpage->analyzing = false;
        $fbpage->save();
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
