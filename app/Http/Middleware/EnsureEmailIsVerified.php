<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {

        if (
            !$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail())
        ) {

            return response()->json([
                'message' => 'Your email address is not verified.',
                'verified' => false
            ], 403);
        }
        return $next($request);
    }
}
