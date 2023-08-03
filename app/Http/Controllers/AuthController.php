<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function showRegistrationForm()
    {
    return view('auth.register');
    }
    public function showPasswordResetForm()
{
    return view('auth.passwords.reset');
}
}
