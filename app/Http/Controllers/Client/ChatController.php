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
        // Step 1: Retrieve all users except the current one
        $users = User::where('id', '!=', $current_user_id)->get();

        // Step 2: Retrieve latest message data for each user
        $latestMessages = DB::table('messages')
            ->select(
                'messages.creator_id AS user_id',
                DB::raw('MAX(messages.created_at) AS last_message_time'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(messages.content ORDER BY messages.created_at DESC), \',\', 1) AS latest_message')
            )
            ->leftJoin('message_recipients', 'messages.id', '=', 'message_recipients.message_id')
            ->where('message_recipients.recipient_id', $current_user_id)
            ->groupBy('messages.creator_id')
            ->get()
            ->keyBy('user_id');

        // Step 3: Merge users with latest message data
        $users->transform(function ($user) use ($latestMessages) {
            $latest = $latestMessages->get($user->id);
            $user->last_message_time = $latest->last_message_time ?? null;
            $user->latest_message = $latest->latest_message ?? null;
            return $user;
        });

        logger('data', compact('users'));
        return view('chatroom', compact('users'));
    }
}
