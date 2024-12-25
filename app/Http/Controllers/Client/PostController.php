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

        if ($request->ajax()) {
            return view('components/posts-partials', compact('posts')); // Return the view containing the new posts
        }

        return view('dashboard', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|required|max:255',
            'content' => 'string|nullable',
            'image' => 'image|max:2048|nullable',
            'type' => 'string|required'
        ]);

        if ($request->hasFile('image')) {
            $get_file_image = $request->file('image');
            $get_image_name = $get_file_image->getClientOriginalName();
            $get_image_extension = $get_file_image->getClientOriginalExtension();
            $image_name = current(explode('.', $get_image_name));
            $imagePath = time() . "-" . $image_name . "." . $get_image_extension;
            $get_file_image->move('./client/image/', $imagePath);
        } else {
            $imagePath = null;
        }

        Post::create([
            "creator_id" => Auth::user()->id,
            "type" => $request->type,
            "title" => $request->title,
            "content" => $request->content,
            "image_url" => $imagePath,
        ]);
        return back();
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

    public function destroy(Request $request)
    {
        $post = Post::find($request->post_id);

        if ($post->creator_id == Auth::id()){
            $post->status = 'deleted';
            $post->save();
        }
    }
}
