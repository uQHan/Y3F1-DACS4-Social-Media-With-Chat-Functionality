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
        $user = User::find(Auth::user()->user_id);
        $posts = User::find(Auth::user()->user_id)->posts()->paginate(10);
        if ($request->expectsJson()) {
            return response()->json(compact('user', 'posts'));
        } else {
            return view('client.profile', compact('user', 'posts'));
        }
    }
    public function other(Request $request)
    {
        if ($request->expectsJson()) {
            $id = auth('sanctum')->user()->user_id;
            $posts = Post::whereIn('user_id', $id)->with('user.settings', 'comments.user.settings')->withCount('bookmarks', 'comments', 'likes')->orderBy('created_at', 'desc')->paginate(4);
            return response()->json(compact('user', 'posts'));
        } else {
            $user_id = $request->user_id;
            $user = User::find($user_id);
            $posts = User::find($user_id)->posts()->paginate(10);
            return view('client.profile', compact('user', 'posts'));
        }
    }
    public function settings()
    {
        return view('client.settings');
    }
}
