<?php

namespace App\Http\Controllers;

use App\PostMark;
use Illuminate\Http\Request;
use Cache;
use App\Http\Requests;
use App\FacebookPage;
use App\FacebookPost;
use App\FacebookUser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination;
use Illuminate\Support\Facades\Response;
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
        $this->fb = \App\Providers\FacebookApiServiceProvider::get();
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

        // Datum des zuletzt hinzugefügten Usereintrags ziehen
        $lastEntry = $this->getLastAnalysis($fbpage);
        $lastAnalysis = $lastEntry ? strtotime($lastEntry) + 1 : 0;

        foreach ($fbpage->posts()->get() as $post) {
            try {
                // Holen der an diesem Post markierten Facebook Seiten
                $markedPages = PostMark::where('post_id', $post->id)->get();
                $markedPagesIds = [];
                foreach ($markedPages as $postMark) {
                    $markedPagesIds[] = $postMark->facebook_id;
                }

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

                    // Überspringe User, wenn er eine markierte Seite geliket hat
                    if ($markedPages) {
                        $likedPages = $this->fb->get($data['id'] . '/likes')->getGraphEdge()->all();
                        foreach ($likedPages as $page) {
                            if (in_array($page['id'], $markedPagesIds)) {
                                continue 2;
                            }
                        }
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

        // Alten Cache löschen und neuen Cache erstellen
        Cache::tags(['results', $fbpage->id])->flush();
        $fbusers = $fbpage->users();
        $users = $fbusers->sortByDesc('count')->forPage(1, 15);
        Cache::tags(['results', $fbpage->id])->forever('all_users', $fbusers);
        Cache::tags(['results', $fbpage->id])->forever(1, $users);

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
            $fbusers = Cache::tags(['results', $fbpage->id])->get('all_users');
            $users = Cache::tags(['results', $fbpage->id])->get($page);
        } else {
            $fbusers = $fbpage->users();
            $users = $fbusers->sortByDesc('count')->forPage($page, 15);
        }

        $pagination = new Pagination\LengthAwarePaginator($fbusers->all(), $fbusers->count(), 15, $page);
        $pagination->setPath($request->getPathInfo());

        if ($fbusers->count()) {
            Cache::tags(['results', $fbpage->id])->forever('all_users', $fbusers);
            Cache::tags(['results', $fbpage->id])->forever($page, $users);
        }

        return view('fbpage/results', compact('fbpage', 'users', 'pagination'));
    }

    /**
     * Generiert für eine Facebook Seite eine CSV Datei
     * mit den gesammelten Nutzerdaten, sortiert nach Relevanz
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function export(Request $request) {
        $fbpage = $request->get('fbpage');
        $fbusers = $fbpage->users()->sortByDesc('count');

        $callback = function() use ($fbusers) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, ['Facebook ID', 'Name', 'Anzahl']);

            foreach($fbusers as $user) {
                fputcsv($handle, [$user['facebook_id'], $user['name'], $user['count']]);
            }

            fclose($handle);
        };

        $filename = 'export-' . date('d-m-y-') . strtolower(str_replace(' ', '-', $fbpage->name));

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename . '.csv'
        ];
        
        return Response::stream($callback, 200, $headers);
    }


    /**
     * Löscht alle Nutzerdaten der Facebook Seite
     *
     * @param Request $request
     * @return Redirect
     */
    public function reset(Request $request) {
        $fbpage = $request->get('fbpage');
        $postIds = $fbpage->posts()->pluck('id')->all();
        $users = FacebookUser::whereIn('facebook_post_id', $postIds);
        $users->delete();
        Cache::tags(['results', $fbpage->id])->flush();

        return Redirect::back();
    }

    /**
     * Gibt den Zeitpunkt der zuletzt abgeschlossenen Analyse zurück
     *
     * @param FacebookPage $fbpage
     * @return \DateTime|null
     */
    public static function getLastAnalysis($fbpage) {
        $lastAnalysis = $fbpage->users()->sortBy(function($fbuser) {
            return $fbuser->updated_at;
        })->last();

        return $lastAnalysis ? $lastAnalysis->updated_at : null;
    }

}
