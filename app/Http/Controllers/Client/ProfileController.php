<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::user()->user_id);
        $blogs = User::find(Auth::user()->user_id)->blogs()->paginate(10);
        if ($request->expectsJson()) {
            return response()->json(compact('user', 'blogs'));
        } else {
            return view('client.profile', compact('user', 'blogs'));
        }
    }
    public function other(Request $request)
    {
        if ($request->expectsJson()) {
            $id = auth('sanctum')->user()->user_id;
            $blogs = Blog::whereIn('user_id', $id)->with('user.settings', 'comments.user.settings')->withCount('bookmarks', 'comments', 'likes')->orderBy('created_at', 'desc')->paginate(4);
            return response()->json(compact('user', 'blogs'));
        } else {
            $user_id = $request->user_id;
            $user = User::find($user_id);
            $blogs = User::find($user_id)->blogs()->paginate(10);
            return view('client.profile', compact('user', 'blogs'));
        }
    }
    public function settings()
    {
        return view('client.settings');
    }
}
