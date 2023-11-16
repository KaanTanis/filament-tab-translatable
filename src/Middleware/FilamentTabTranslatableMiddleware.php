<?php

namespace KaanTanis\FilamentTabTranslatable\Middleware;

use Closure;
use KaanTanis\FilamentTabTranslatable\Helpers\Helper;

class FilamentTabTranslatableMiddleware
{
    public function handle($request, Closure $next)
    {
        if(! $request->user()) {
            return $next($request);
        }

        $supported_languages = Helper::getLangCodes(); // reorder by default true. So first lang is default lang
        if (request()->segment(1) == $supported_languages[0]) {
            return $next($request);
        } elseif (in_array(request()->segment(1), $supported_languages)) {
            $request->merge([
                'locale' => request()->segment(1)
            ]);

            return $next($request);
        }

        return $next($request);
    }
}