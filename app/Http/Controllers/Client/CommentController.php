<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'blogID' => 'required',
            'commentText' => 'required',
            'commentImage' => 'image|max:2048|nullable'
        ]);
        if ($request->hasFile('commentImage')) {
            $get_file_image = $request->file('commentImage');
            $get_image_name = $get_file_image->getClientOriginalName();
            $get_image_extension = $get_file_image->getClientOriginalExtension();
            $image_name = current(explode('.', $get_image_name));
            $imagePath = time() . "-" . $image_name . "." . $get_image_extension;
            $get_file_image->move('./client/image/', $imagePath);
        } else {
            $imagePath = "placeholder.png";
        }
        if ($request->expectsJson()){
            $id = auth('sanctum')->user()->user_id;
        } else {
            $id = auth()->user()->user_id;         
        }
        Comment::create([
            "user_id" => $id,
            "blog_id" => $request->blogID,
            "content" => $request->commentText,
            "image_url" => $imagePath,
        ]);
        if ($request->expectsJson()){
            return response();
        } else {
            return back();          
        }
    }
}
