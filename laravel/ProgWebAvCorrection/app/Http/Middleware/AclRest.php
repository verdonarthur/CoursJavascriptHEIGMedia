<?php namespace App\Http\Middleware;

use Closure;

class AclRest {

   /**
    * Middleware ACL pour REST en fixant les droits en fonction du nom de la route REST
    *
    * Prérequis: ce middleware doit venir après le middleware 'auth' de Laravel
    *
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $action = $request->route()->getAction();
        $routeName = $action['as'];
        $user = $request->user();
        if (!$user->hasPermission($routeName)) {
            return response('Forbidden', 403);
        }
        return $next($request);
    }

}
