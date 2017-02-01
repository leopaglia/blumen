<?php

namespace App\Http\Middleware;

use Closure;

class ExampleMiddleware {

    /**
     * Handle request previous to the controller
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
