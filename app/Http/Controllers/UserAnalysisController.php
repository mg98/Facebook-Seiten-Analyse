<?php

namespace App\Http\Controllers;

use App\Providers\FacebookApiServiceProvider;
use Illuminate\Http\Request;
use Cache;
use App\Http\Requests;
use App\FacebookPage;
use App\FacebookPost;
use App\FacebookUser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination;
use \Illuminate\View\View;

class UserAnalysisController extends Controller
{

    /**
     * @var Facebook $fb
     */
    public $fb = null;

    /**
     * Facebook Graph API ansprechen
     */
    public function __construct() {
        $this->fb = FacebookApiServiceProvider::get();
    }

    /**
     * Startet Nutzeranalyse (Aufruf über AJAX)
     *
     * @param Request $request
     * @return string
     */
    public function start(Request $request) {
        ini_set('max_execution_time', 3600);

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
        Cache::tags(['results', $fbpage->id])->flush();
        $fbusers = $fbpage->users();
        $result_page_path = substr($request->getPathInfo(), 0, nth_strpos($request->getPathInfo(), '/', 3));
        for ($page = 1; $page < 3; $page++) {
            $users = $fbusers->sortByDesc('count')->forPage($page, 15);
            $pagination = new Pagination\LengthAwarePaginator($fbusers->all(), $fbusers->count(), 15, $page);
            $pagination->setPath($result_page_path);
            $result_page = view('fbpage/results', compact('fbpage', 'users', 'pagination'))->render();
            Cache::tags(['results', $fbpage->id])->forever($page, $result_page);
        }

        $fbpage->analyzing = false;
        $fbpage->save();

        exit('success');
    }

    /**
     * Stoppt eine laufende Analyse
     *
     * @param Request $request
     * @return mixed
     */
    public function stop(Request $request) {
        $fbpage = $request->get('fbpage');
        $fbpage->analyzing = false;
        $fbpage->save();
        return Redirect::back();
    }



    /**
     * Ergebnisseite einer Seite/ Auswertung der Analyse
     *
     * @param Request $request
     * @return View
     */
    public function showResults(Request $request) {
        $page = isset($_GET['page']) && intval($_GET['page']) ? $_GET['page'] : 1;
        $fbpage = $request->get('fbpage');

        if (Cache::tags(['results', $fbpage->id])->has($page)) {
            return Cache::tags(['results', $fbpage->id])->get($page);
        }

        $fbusers = $fbpage->users();
        $users = $fbusers->sortByDesc('count')->forPage($page, 15);
        $pagination = new Pagination\LengthAwarePaginator($fbusers->all(), $fbusers->count(), 15, $page);
        $pagination->setPath($request->getPathInfo());

        $result_page = view('fbpage/results', compact('fbpage', 'users', 'pagination'))->render();
        Cache::tags(['results', $fbpage->id])->forever($page, $result_page);

        return $result_page;
    }
}
