<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $current_user_id = Auth::id();
        $users = User::select(
            'users.id',
            'users.name',
            'users.pfp_url',
            DB::raw('MAX(messages.created_at) as last_message_time'),
            'messages.content as latest_message'
        )
        ->leftJoin('message_recipients', function ($join) use ($current_user_id) {
            $join->on('users.id', '=', 'message_recipients.recipient_id')
                 ->where('message_recipients.recipient_id', $current_user_id);
        })
        ->leftJoin('messages', 'message_recipients.message_id', '=', 'messages.id')
        ->where('users.id', '!=', $current_user_id)
        ->groupBy('users.id', 'users.name', 'users.pfp_url', 'messages.content')
        ->orderByRaw('COALESCE(MAX(messages.created_at), 0) DESC')->get();

        return view('chatroom', compact('users'));
    }
}
