<?php

namespace App\Http\Middleware;

use App\Models\About;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceSingleAbout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $exists = About::exists();

        if ($exists && in_array($request->route()->getName(), [
            'manage.about.create',
            'manage.about.store',
        ])) {
            return redirect()->route('manage.about.edit', About::first()->id)
                ->with('info', 'About us already exists. You can edit it here.');
        }

        if (! $exists && in_array($request->route()->getName(), [
            'manage.about.edit',
            'manage.about.update',
        ])) {
            return redirect()->route('manage.about.create');
        }

        return $next($request);
    }
}
