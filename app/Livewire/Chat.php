<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Group;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class Chat extends Component
{
    public $content;
    public $recipient;
    public $group = null;
    public $history = [];
    public $userId;
    public $user;
    public $authId;
    protected $rules = [
        'content' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->authId = Auth::id();
    }
    public function getListeners()
    {
        return [
            "loadMessages" => 'loadMessages',
            "echo-private:chat.{$this->authId},MessageSent" => 'onMessageSent',
            "loadGroupMessages" => 'loadGroupMessages',
            "echo-presence:groupchat.{$this->group},MessageSent" => 'onGroupMessageSent',
        ];
    }

    public function onMessageSent($event)
    {
        if ($this->userId == $event['message']['creator_id']) {
            $this->loadMessages($this->userId);
        }
    }

    public function onGroupMessageSent($event)
    {
        if ($this->group == $event['group']) {
            $this->loadGroupMessages($this->group);
        }
    }

    // Load chat messages when a user is selected
    public function loadMessages($userId)
    {
        $this->userId = $userId;
        $this->user = User::find($userId);

        // Get messages between the current user and the selected user
        $this->history = Message::join('message_recipients', 'messages.id', '=', 'message_recipients.message_id')
            ->where(function ($query) use ($userId) {
                $query->where('messages.creator_id', $this->authId)
                    ->where('message_recipients.recipient_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('messages.creator_id', $userId)
                    ->where('message_recipients.recipient_id', $this->authId);
            })
            ->select(
                'messages.*',
                'message_recipients.recipient_group_id'
            )
            ->orderBy('messages.created_at', 'asc')
            ->get();
        // $this->dispatch('latestMessage');
    }

    // Load chat messages when a group is selected
    public function loadGroupMessages($group)
    {
        $this->group = $group;
        // Get messages between the current user and the selected user
        $this->history = Message::join('message_recipients', 'messages.id', '=', 'message_recipients.message_id')
            ->where('message_recipients.recipient_group_id', $group)
            ->select('messages.*', 'message_recipients.recipient_group_id')
            ->orderBy('messages.created_at', 'asc')
            ->get();
    }

    // Send message
    public function sendMessage()
    {
        $this->validate();

        DB::transaction(function () {
            // Create the message
            $message = Message::create(['content' => $this->content, 'creator_id' => Auth::id()]);
            if (!$message) {
                throw new \Exception("Failed to create message.");
            }

            // Create recipients for group users
            if ($this->group) {
                $group = Group::with('users')->findOrFail($this->group);

                // Prepare recipients for group
                $recipients = $group->users->map(function ($user) use ($message) {
                    return [
                        'message_id' => $message->id, // Ensure $message is accessible
                        'recipient_id' => $user->id,
                        'recipient_group_id' => $this->group,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                });

                // Insert all group recipients
                MessageRecipient::insert($recipients->toArray());
            }

            // Create recipient for individual user
            if ($this->userId) {
                MessageRecipient::create([
                    'message_id' => $message->id,
                    'recipient_id' => $this->userId,
                ]);
            }

            // Broadcast the event
            broadcast(new MessageSent($message, $this->userId, $this->group));
        });

        // Clear input and reload messages
        $this->content = '';
        $this->loadMessages($this->userId);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
