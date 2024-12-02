<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserRegisterController extends Controller
{
    public function index()
    {
        return view('client.welcome');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registerEmail' => 'required|email|unique:users,email',
            'registerPassword' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response();
            } else {
                return back()->withErrors($validator);
            }
        }

        $validator->validated();

        $user = User::create([
            'email' => $request->registerEmail,
            'password' => $request->registerPassword,
        ]);

        Auth::loginUsingId($user->user_id);

        if (!$user->settings()->exists()) {
            if ($request->expectsJson()) {
                return response();
            } else {
                return redirect('/setup-account');
            }
        }
        if ($request->expectsJson()) {
            $token = $request->user()->createToken('access_token');
            if (!User::find(Auth::user()->user_id)->settings()->exists())
                return response()->json(['token' => $token->plainTextToken, 'settings' => 'false']);
            return response()->json(['token' => $token->plainTextToken, 'settings' => 'true']);
        } else {
            return redirect('/home');
        }
    }
}
