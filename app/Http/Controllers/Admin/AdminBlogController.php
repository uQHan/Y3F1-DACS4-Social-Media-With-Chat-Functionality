<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class AdminBlogController extends Controller
{
    public function blogs(){
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(5);
        return view('admin.list-blogs', compact('blogs'));
    }
    public function remove($blog_id){
        $remove_blog = Blog::find($blog_id);
        $remove_blog->deleted = true;
        $remove_blog->save();
        return back();
    }
}
