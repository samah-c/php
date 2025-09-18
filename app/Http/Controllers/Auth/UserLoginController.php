<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UserLoginController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/UserLogin');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by login
        $user = Utilisateur::where('login', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        // Check if user is active
        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'login' => ['Votre compte est désactivé ou expiré.'],
            ]);
        }

        Auth::guard('utilisateur')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('utilisateur')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}