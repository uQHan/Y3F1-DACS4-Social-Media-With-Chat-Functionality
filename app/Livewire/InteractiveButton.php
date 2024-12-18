<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InteractiveButton extends Component
{
    public $item; // The model instance (e.g., Post, User, etc.)
    public $type; // The interaction type (e.g., "like", "bookmark")
    public $isActive; // Tracks whether the action is active
    public $count; // Tracks the total count of interactions
    public $icon; // The icon to be rendered

    public function mount($item, $type)
    {
        $this->item = $item;
        $this->type = $type;
        if ($type == 'like') $this->icon = 'heart';
        else $this->icon = $type;

        // Initialize active state and count dynamically
        $this->isActive = $item->{$type . 's'}()->where('user_id', Auth::id())->exists();
        $this->count = $item->{$type . 's'}()->count();
    }

    public function toggleAction()
    {
        echo("<script>console.log('damned');</script>");
        if ($this->isActive) {
            $this->item->{$this->type . 's'}()->detach(Auth::id());
            $this->isActive = false;
            $this->count--;
        } else {
            $this->item->{$this->type . 's'}()->attach(Auth::id());
            $this->isActive = true;
            $this->count++;
        }
    }

    public function render()
    {
        return view('livewire.interactive-button', [
            'isActive' => $this->isActive,
            'count' => $this->count,
            'icon' => $this->icon
        ]);
    }
}
