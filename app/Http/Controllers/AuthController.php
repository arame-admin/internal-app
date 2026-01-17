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
        // Skip authentication for UI development - always redirect to dashboard
        // Remove this line when implementing real authentication
        // Auth::loginUsingId(1); // Uncomment to test with a specific user
        
        return redirect()->intended('/dashboard');
    }
}

