<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUtilisateur
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('utilisateur')->check()) {
            return redirect()->route('user.login');
        }

        $user = Auth::guard('utilisateur')->user();
        if (!$user->isActive()) {
            Auth::guard('utilisateur')->logout();
            return redirect()->route('user.login')
                ->withErrors(['login' => 'Votre compte est désactivé ou expiré.']);
        }

        return $next($request);
    }
}