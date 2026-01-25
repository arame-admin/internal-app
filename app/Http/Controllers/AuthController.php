<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * AuthController handles user authentication operations.
 *
 * This controller manages user login, logout, and related authentication
 * processes. It includes validation, session management, role-based redirection,
 * and comprehensive logging for security and debugging purposes.
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View The login view
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle user login attempt.
     *
     * Validates credentials, attempts authentication, regenerates session,
     * logs the attempt, and redirects based on user role.
     *
     * @param Request $request The HTTP request containing email and password
     * @return \Illuminate\Http\RedirectResponse Redirect to dashboard or back with errors
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();

                $user = Auth::user();
                $roleId = $user->role_id;

                Log::channel('custom')->info('User logged in: ' . $user->email);

                // Redirect based on role
                switch ($roleId) {
                    case 1: // Admin
                        return redirect()->intended('/admin/dashboard');
                    case 2: // Manager
                        return redirect()->intended('/admin/dashboard');
                    case 3: // Employee
                        return redirect()->intended('/admin/dashboard');
                    default:
                        return redirect()->intended('/admin/dashboard');
                }
            }

            Log::channel('custom')->warning('Failed login attempt for email: ' . $request->email);

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } catch (\Exception $e) {
            Log::channel('custom')->error('Error during login: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'An error occurred during login.',
            ]);
        } finally {
            Log::channel('custom')->info('Login method executed');
        }
    }

    /**
     * Handle user logout.
     *
     * Logs out the user and redirects to the login page.
     *
     * @param Request $request The HTTP request
     * @return \Illuminate\Http\RedirectResponse Redirect to login page
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}

