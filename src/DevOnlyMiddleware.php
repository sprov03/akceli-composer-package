<?php

namespace Akceli;

use Closure;
use Illuminate\Support\Facades\App;

class DevOnlyMiddleware
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
        if (App::environment(['local', 'dev', 'testing'])) {
            return $next($request);
        }

        abort(405);
    }
}