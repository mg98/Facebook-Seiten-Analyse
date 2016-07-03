<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\FacebookPage;
use App\FacebookPost;
use App\FacebookUser;
use \Illuminate\View\View;

class PostMarkingController extends Controller
{

    /**
     * Rendert Seite zum Verwalten markierter
     * Facebook-Seiten in einem Post
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) {
        $fbpage = $request->get('fbpage');
        $post = $request->get('fbpost');

        return view('fbpage/mark', compact('fbpage', 'post'));
    }

    /**
     * Neue Markierung in Facebook Post hinzufügen
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

        return Redirect::back();
    }

}
