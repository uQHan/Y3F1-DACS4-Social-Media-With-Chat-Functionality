<?php

namespace App\Livewire;

use Livewire\Component;

class ChatUserList extends Component
{
    public $users;
    public function mount($users){
        $this->users = $users;
    }
    public function loadUsers($id){
        $this->dispatch('loadMessages', $id);
    }
    public function loadGroups($id){
        $this->dispatch('loadGroupMessages', $id);
    }
    public function render()
    {
        return view('livewire.chat-user-list');
    }
}
