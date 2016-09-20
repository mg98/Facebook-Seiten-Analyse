<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{

    public function showSettings() {
        return view('user/settings');
    }

    public function update(Request $request) {
        $user = \Auth::user();
        $properties = ['fb_app_id', 'fb_app_secret', 'fb_accesstoken'];
        foreach ($properties as $property) {
            $user->$property = $request->get($property);
        }
        $user->save();

        return Redirect::back();
    }

}
