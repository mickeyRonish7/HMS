<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has a locale preference
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
        } 
        // Otherwise check session
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');
        } 
        // Default to English
        else {
            $locale = config('app.locale', 'en');
        }

        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}
