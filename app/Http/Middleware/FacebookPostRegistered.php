<?php

namespace App\Http\Middleware;

use Closure;
use \App\FacebookPost;
use \Illuminate\Http\Request;

class FacebookPostRegistered
{
    /**
     * Überprüft ob der angeforderte Post existiert
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $fbpost = FacebookPost::find($request->fbpost);

        if (!$fbpost->exists()) {
             abort(404, 'Der angeforderte Facebook Post konnte nicht gefunden werden.');
        } else {
            $request->attributes->add(['fbpost' => $fbpost]);
            return $next($request);
        }
    }
}
