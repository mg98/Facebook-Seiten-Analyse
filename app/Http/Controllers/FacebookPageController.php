<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Facebook\Facebook;
use \App\FacebookPage;

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

        $response = $this->fb->get($request->get('page'));
        $pageNode = $response->getGraphPage()->all();

        $fbp = new FacebookPage;
        $fbp->name = $pageNode['name'];
        $fbp->facebook_id = $pageNode['id'];
        $fbp->save();

    }

}
