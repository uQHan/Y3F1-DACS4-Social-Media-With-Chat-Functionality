<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            if (auth('sanctum')->check()) {
                // $blogs = Blog::orderBy('created_at', 'desc')->paginate(10);
                $blogs = Blog::with('user.settings', 'comments.user.settings')
                ->withCount('bookmarks', 'comments', 'likes')->orderBy('created_at', 'desc')->paginate(4);
                return response()->json(compact('blogs'));
            }
        }
        if (Auth::check()) {
            $blogs = Blog::orderBy('created_at', 'desc')->paginate(10);
            return view('client.home', compact('blogs'));
        } else {
            return view('client.welcome');
        }
    }

    public function store(Request $request)
    {
        if (Auth::attempt(['email' => $request->loginEmail, 'password' => $request->loginPassword], $request->loginRemember)) {
            if (!$request->expectsJson()) {
                $request->session()->regenerate();
                Auth::user()->user_id;
                if (!User::find(Auth::user()->user_id)->settings()->exists())
                    return redirect('/setup-account');
                return redirect('/home');
            } else {
                $token = $request->user()->createToken('access_token');
                if (!User::find(Auth::user()->user_id)->settings()->exists())
                    return response()->json(['token' => $token->plainTextToken, 'settings' => 'false']);
                return response()->json(['token' => $token->plainTextToken, 'settings' => 'true']);
            }
        } else {
            if ($request->expectsJson()) {
                return response('false');
            } else {
                return back()->withErrors([
                    'email' => 'Email or password is incorrect.',
                ]);
            }
        }
    }

    public function getUser(Request $request)
    {
        $id = auth('sanctum')->user()->user_id;
        $user = User::with('settings')->find($id);
        // $name = $user->settings->username;
        // $email = $user->email;
        // $dob = $user->settings->dob;
        // $pfpUrl = $user->settings->pfp_url;
        // $bio = $user->settings->bio;
        // $website = $user->settings->website;

        if ($request->expectsJson()) {
            return response()->json(compact('user'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->bearerToken();
        if ($request->expectsJson()) {
            return response();
        } else {
            return redirect('/welcome');
        }
    }
}
