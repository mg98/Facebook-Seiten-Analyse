<?php

namespace App\Http\Middleware;

use Closure;
use \App\FacebookPage;

class FacebookPageRegistered
{
    /**
     * Überprüft ob die angeforderte Facebook Seite registriert ist
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $fbpage = FacebookPage::where('name', nice_decode($request->fbpage));

        if (!$fbpage->exists()) {
             abort(404, 'Die angeforderte Facebook-Seite ist nicht registriert.');
        } else {
            $request->attributes->add(['fbpage' => $fbpage->first()]);
            return $next($request);
        }
    }
}
