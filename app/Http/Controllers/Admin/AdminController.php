<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        $usersCount = User::all()->count();
        $blogsCount = Blog::all()->count();
        $likesCount = Like::all()->count();
        $bookmarksCount = Bookmark::all()->count();
        return view('admin.index', compact('usersCount','blogsCount','likesCount','bookmarksCount'));
    }
}
