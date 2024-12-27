<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $posts = User::find(Auth::id())->posts()->paginate(5);
        return view('profile.index', compact('user', 'posts'));
    }
    public function other(Request $request)
    {
        $user = User::find($request->id);
        $posts = User::find($request->id)->posts()->paginate(5);
        return view('profile.index', compact('user', 'posts'));
    }
    public function settings()
    {
        return view('client.settings');
    }
}
