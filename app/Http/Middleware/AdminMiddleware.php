<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $action_method = $request->route()->getActionMethod();
        View::share('action_method', $action_method);

        $user = auth()->user();
        View::share('session_user', $user);

        $action_method = $request->route()->getActionMethod();

        $form_action = '';
        $form_method = 'POST';

        if(Route::has($request->segment(1) . '.store')) {
            $form_action = route($request->segment(1) . '.store');
        }

        if($action_method == 'edit') {
            $route_params = $request->route()->parameters();

            if(Route::has($request->segment(1) . '.update')) {
                $form_action = route($request->segment(1) . '.update', $route_params);
            }

            $form_method = 'PUT';
        }

        View::share('form_action', $form_action);
        View::share('form_method', $form_method);

        return $next($request);
    }
}
