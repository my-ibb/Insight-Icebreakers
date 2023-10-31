<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // ユーザーがログインしており、かつロールが 'admin' である場合にのみ次の処理へ
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // ユーザーがログインしていない、またはロールが 'admin' でない場合はホームにリダイレクト
        return redirect('/');
    }
}
