<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\User;
use App\Services\Client\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(5);
        if ($request->expectsJson()) {
            return response()->json($blogs);
        } else {
            return view('client.home', compact('blogs'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'postTitle' => 'string|required',
            'postText' => 'string|nullable',
            'postImage' => 'image|max:2048|nullable'
        ]);

        if ($request->hasFile('postImage')) {
            $get_file_image = $request->file('postImage');
            $get_image_name = $get_file_image->getClientOriginalName();
            $get_image_extension = $get_file_image->getClientOriginalExtension();
            $image_name = current(explode('.', $get_image_name));
            $imagePath = time() . "-" . $image_name . "." . $get_image_extension;
            $get_file_image->move('./client/image/', $imagePath);
        } else {
            $imagePath = null;
        }
        if ($request->expectsJson()){
            $id = auth('sanctum')->user()->user_id;
        } else {
            $id = auth()->user()->user_id;         
        }
        Blog::create([
            "user_id" => $id,
            "title" => $request->postTitle,
            "content" => $request->postText,
            "image_url" => $imagePath,
        ]);
        if ($request->expectsJson()) {
            return response();
        } else {
            return back();
        }
    }

    public function like(Request $request)
    {
        $blog_id = $request->blog_id;
        if ($request->expectsJson() && auth('sanctum')->check()) {
            $id = auth('sanctum')->user()->user_id;
            $user = User::find($id);
        } else
            $user = User::find(auth()->user()->user_id);
        $hasLike = $user->likes()->where('likes.blog_id', $blog_id)->exists();
        if (!$hasLike) {
            $user->likes()->attach($blog_id);
        } else {
            $user->likes()->detach($blog_id);
        }
        if (!$request->expectsJson())
            return back();
    }
    public function isLiked(Request $request)
    {
        $blog_id = $request->blog_id;
        $id = auth('sanctum')->user()->user_id;
        $user = User::find($id);
        $hasLike = $user->likes()->where('likes.blog_id', $blog_id)->exists();
        if ($hasLike) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function bookmark(Request $request)
    {
        $blog_id = $request->blog_id;
        if ($request->expectsJson() && auth('sanctum')->check()) {
            $id = auth('sanctum')->user()->user_id;
            $user = User::find($id);
        } else
            $user = User::find(auth()->user()->user_id);
        $hasBookmark = $user->bookmarks()->where('bookmarks.blog_id', $blog_id)->exists();
        if (!$hasBookmark) {
            $user->bookmarks()->attach($blog_id);
        } else {
            $user->bookmarks()->detach($blog_id);
        }
        if (!$request->expectsJson())
            return back();
    }

    public function isBookmarked(Request $request)
    {
        $blog_id = $request->blog_id;
        $id = auth('sanctum')->user()->user_id;
        $user = User::find($id);
        $hasBookmark = $user->bookmarks()->where('bookmarks.blog_id', $blog_id)->exists();
        if ($hasBookmark) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function bookmarkList(Request $request)
    {
        $blogs = User::find(auth()->user()->user_id)->bookmarks()->orderBy('bookmarks.created_at', 'desc')->paginate(10);
        if ($request->expectsJson()) {
            return response()->json($blogs);
        } else {
            return view('client.bookmark', compact('blogs'));
        }
    }
}
