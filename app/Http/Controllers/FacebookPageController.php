<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Facebook\Facebook;
use \App\FacebookPage;
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

        $fbp = new FacebookPage;
        $fbp->name = $pageNode['name'];
        $fbp->facebook_id = $pageNode['id'];
        $fbp->save();

        $request->session()->flash('success', 'Die Facebook Seite "'.$pageNode['name'].'" wurde erfolgreich hinzugefügt!');
        return view('fbpage.new');
    }

    public function show($fbpage) {
        $name = niceDecode($fbpage);
        $page = FacebookPage::where('name', $name);

        if (!$page->exists()) {
            return Redirect::to('404');
        }



    }

}
