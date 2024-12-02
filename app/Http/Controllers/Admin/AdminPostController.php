<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    public function posts(){
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
        return view('admin.list-posts', compact('posts'));
    }
    public function remove($post_id){
        $remove_post = Post::find($post_id);
        $remove_post->deleted = true;
        $remove_post->save();
        return back();
    }
}
