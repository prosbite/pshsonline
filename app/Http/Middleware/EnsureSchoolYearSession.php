<?php

namespace App\Http\Middleware;

use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolYearSession
{
    /**
     * Keep the session school year in sync with the selected current school year.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            SchoolYear::syncSession(SchoolYear::current());
        }

        return $next($request);
    }
}
