<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $roleId = $user->role_id;
            
            // Redirect based on role
            switch ($roleId) {
                case 1: // Admin
                    return redirect()->intended(route('admin.dashboard'));
                case 2: // Manager
                    return redirect()->intended(route('manager.dashboard'));
                case 3: // Employee
                    return redirect()->intended(route('employee.dashboard'));
                default:
                    return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}

