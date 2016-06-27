<?php

namespace App\Http\Middleware;

use Closure;
use \App\FacebookPage;
use Illuminate\Support\Facades\Redirect;

class FacebookPageRegistered
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $fbpage = FacebookPage::where('name', niceDecode($request->fbpage));

        if (!$fbpage->exists()) {
             abort(404, 'Die angeforderte Facebook-Seite ist nicht registriert.');
        } else {
            $request->attributes->add(['fbpage' => $fbpage->first()]);
            return $next($request);
        }
    }
}
