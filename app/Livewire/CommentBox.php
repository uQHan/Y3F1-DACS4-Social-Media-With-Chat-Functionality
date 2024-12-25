<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class CommentBox extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $post_id;
    public $content;
    protected $rules = [
        'content' => 'required|string|max:255',
    ];
    public function mount($post_id)
    {
        $this->post_id = $post_id;
    }
    public function comment(){
        $this->validate();

        Comment::create([
            "creator_id" => Auth::id(),
            "post_id" => $this->post_id,
            "content" => $this->content,
            // "image_url" => $imagePath,
        ]);

        $this->reset('content');

        $this->dispatch('commentAdded');
    }
    public function render()
    {
        return view('livewire.comment-box',[
            'comments' => Comment::where('post_id', $this->post_id)->paginate(5),
        ]);
    }
}
