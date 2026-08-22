<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['id', 'en', 'ja'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = Auth::check()
            ? Auth::user()->locale
            : $request->session()->get('locale');

        if ($locale && in_array($locale, self::SUPPORTED, true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
