<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\User;
use App\Services\Client\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
        if ($request->expectsJson()) {
            return response()->json($posts);
        } else {
            return view('client.home', compact('posts'));
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
            $id = auth('sanctum')->user()->id;
        } else {
            $id = Auth::user()->id;         
        }
        Post::create([
            "creator_id" => $id,
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
        $post_id = $request->post_id;
        if ($request->expectsJson() && auth('sanctum')->check()) {
            $id = auth('sanctum')->user()->id;
            $user = User::find($id);
        } else
            $user = User::find(Auth::user()->id);
        $hasLike = $user->likes()->where('likes.post_id', $post_id)->exists();
        if (!$hasLike) {
            $user->likes()->attach($post_id);
        } else {
            $user->likes()->detach($post_id);
        }
        if (!$request->expectsJson())
            return back();
    }
    public function isLiked(Request $request)
    {
        $post_id = $request->post_id;
        $id = auth('sanctum')->user()->id;
        $user = User::find($id);
        $hasLike = $user->likes()->where('likes.post_id', $post_id)->exists();
        if ($hasLike) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function bookmark(Request $request)
    {
        $post_id = $request->post_id;
        if ($request->expectsJson() && auth('sanctum')->check()) {
            $id = auth('sanctum')->user()->id;
            $user = User::find($id);
        } else
            $user = User::find(Auth::user()->id);
        $hasBookmark = $user->bookmarks()->where('bookmarks.post_id', $post_id)->exists();
        if (!$hasBookmark) {
            $user->bookmarks()->attach($post_id);
        } else {
            $user->bookmarks()->detach($post_id);
        }
        if (!$request->expectsJson())
            return back();
    }

    public function isBookmarked(Request $request)
    {
        $post_id = $request->post_id;
        $id = auth('sanctum')->user()->id;
        $user = User::find($id);
        $hasBookmark = $user->bookmarks()->where('bookmarks.post_id', $post_id)->exists();
        if ($hasBookmark) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function bookmarkList(Request $request)
    {
        $posts = User::find(Auth::user()->id)->bookmarks()->orderBy('bookmarks.created_at', 'desc')->paginate(10);
        if ($request->expectsJson()) {
            return response()->json($posts);
        } else {
            return view('client.bookmark', compact('posts'));
        }
    }
}
