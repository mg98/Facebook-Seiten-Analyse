<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\FacebookPage;
use App\FacebookPost;
use App\FacebookUser;
use App\PostMark;
use \Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

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
        $marks = $post->postMarks()->get();

        return view('fbpage/mark', compact('fbpage', 'post', 'marks'));
    }

    /**
     * Neue Markierung in Facebook Post hinzufügen
     *
     * @param Request $request
     * @return View
     */
    public function add(Request $request) {
        $postId = $request->get('fbpost')->id;

        $this->validate($request, [
            'page' => 'isFacebookPage'
        ]);
        $this->validate($request, [
            'page' => 'pageNotMarked:'.$postId
        ]);

        $response = fb()->get($request->get('page'));
        $pageNode = $response->getGraphPage()->all();

        $newPostMark = new PostMark;
        $newPostMark->facebook_post_id = $postId;
        $newPostMark->name = $pageNode['name'];
        $newPostMark->facebook_id = $pageNode['id'];
        $newPostMark->save();

        $request->session()->flash('success', 'Die Facebook Seite "'.$pageNode['name'].'" wurde markiert.');

        return Redirect::back();
    }

    /**
     * Entfernt eine eingetragene Markierung
     *
     * @param Request $request
     * @return View
     */
    public function remove(Request $request) {
        PostMark::destroy($request->mark);

        return Redirect::back();
    }

}
