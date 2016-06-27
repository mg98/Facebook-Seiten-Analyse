<?php

namespace App\Http\Middleware;

use Closure;
use \App\FacebookPost;

class FacebookPostRegistered
{
    /**
     * Überprüft ob der angeforderte Post existiert
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $fbpost = FacebookPost::find($request->fbpost);

        if (!$fbpost->exists()) {
             abort(404, 'Der angeforderte Facebook Post konnte nicht gefunden werden.');
        } else {
            $request->attributes->add(['fbpost' => $fbpost->first()]);
            return $next($request);
        }
    }
}
