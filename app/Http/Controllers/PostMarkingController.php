<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PostMarkingController extends Controller
{

    /**
     * Rendert Seite zum Verwalten markierter Facebook-
     * Seiten in einem Post
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request) {
        $fbpage = $request->get('fbpage');
        $post = $request->get('fbpost');

        return view('fbpage/mark/index', compact('fbpage', 'post'));
    }

}
