<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('staff.login');
    }

    public function store(Request $request)
    {
        if (Auth::attempt(['email' => $request->loginEmail, 'password' => $request->loginPassword, 'role' => 2], $request->loginRemember)) {
            $request->session()->regenerate();
            Auth::user()->user_id;
            return redirect('/dashboard');
        } else {
            return back()->withErrors([
                'email' => 'Email or password is incorrect.',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/welcome');
    }
}
