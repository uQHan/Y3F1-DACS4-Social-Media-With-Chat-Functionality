<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function users(){
        $users = User::paginate(10);
        return view('admin.list_users', compact('users'));
    }

    public function deactivate($user_id){
        $dec = User::find($user_id);
        $dec->deactivated = true;
        $dec->save();
        return back();
    }
}
