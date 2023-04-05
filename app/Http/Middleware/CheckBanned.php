<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !(auth()->user()->status == 1)) {
            return $next($request);
        }

        if(auth()->check() && (auth()->user()->status == 1)){
//            Auth::user()->token()->delete();
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            if (auth()->user()->banned_until) {
                $banned_until = auth()->user()->banned_until->format('d.m.Y');
                return response()->json([
                    'ban' => 1,
                    'message' => 'Ваш аккаунт был заблокирован до '.$banned_until.'. Пожалуйста, свяжитесь с администратором.'],
                    419
                );
            }

            return response()->json(['message' => 'Ваш аккаунт был заморожен. Пожалуйста, свяжитесь с администратором.'], 419);
        }

        return $next($request);
    }
}
