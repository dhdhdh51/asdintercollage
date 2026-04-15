<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shares global site settings with all views.
 */
class SettingsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $settings = Setting::getAll();
            view()->share('siteSettings', $settings);
        } catch (\Exception $e) {
            view()->share('siteSettings', []);
        }

        return $next($request);
    }
}
