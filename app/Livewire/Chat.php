<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\MessageRecipient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class Chat extends Component
{
    public $content;
    public $recipient;
    public $group;
    public $history = [];
    public $userId;
    public $authId;
    protected $rules = [
        'content' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->authId = Auth::id();
    }

    // Load chat messages when a user is selected
    #[On('loadMessages', 'echo:chat.{authId},MessageSent')]
    public function loadMessages($userId)
    {
        $this->userId = $userId;
        // Get messages between the current user and the selected user
        $this->history = Message::join('message_recipients', 'messages.id', '=', 'message_recipients.message_id')
            ->where(function ($query) use ($userId) {
                $query->where('messages.creator_id', Auth::id())
                    ->where('message_recipients.recipient_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('messages.creator_id', $userId)
                    ->where('message_recipients.recipient_id', Auth::id());
            })
            ->select(
                'messages.*',
                'message_recipients.*'
            )
            ->orderBy('messages.created_at', 'asc')
            ->get();
        $this->dispatch('messageSent');
    }

    // Send message
    public function sendMessage()
    {
        $this->validate();

        DB::transaction(function () {
            $message = Message::create(['content' => $this->content, 'creator_id' => Auth::id()]);
            if (!$message) {
                throw new \Exception("Failed to create message.");
            }
            MessageRecipient::create(['message_id' => $message->id, 'recipient_id' => $this->userId, 'recipient_group_id' => $this->group]);
            broadcast(new MessageSent($message, $this->userId, $this->group));
        });
        $this->content = '';
        $this->loadMessages($this->userId);
        $this->dispatch('messageSent');
    }
    public function render()
    {
        return view('livewire.chat');
    }
}
