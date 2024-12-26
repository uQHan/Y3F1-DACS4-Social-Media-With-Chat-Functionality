<?php

namespace App\Livewire;

use Livewire\Component;

class ChatListItem extends Component
{
    public $user;

    public function mount($user)
    {
        $this->user = $user;
    }
    public function loadUsers($id)
    {
        $this->dispatch('loadMessages', $id);
    }
    public function loadGroups($id)
    {
        $this->dispatch('loadGroupMessages', $id);
    }
    public function render()
    {
        return view('livewire.chat-list-item');
    }
}
