<div>
    <ul class="space-y-2 gap-2" wire:key="$post_id">
        @forelse($comments as $comment)
        <li class="flex items-start gap-2" wire:key="$comment->id">
            <img src="{{ asset('client/pfp/'.$comment->creator->pfp_url)}}" alt="User Avatar"
                class="w-10 h-10 rounded-full">
            <div>
                <span class="block font-bold text-gray-900 dark:text-gray-100">{{ $comment->creator->name }}</span>
                <p class="text-sm text-gray-800 dark:text-gray-300">{{ $comment->content }}</p>
            </div>
        </li>
        @empty
        <li class="text-gray-600 dark:text-gray-400">No comments yet.</li>
        @endforelse
        {{ $comments->links(data: ['scrollTo' => false]) }}
    </ul>
    <!-- Create Comment Box -->
    <form wire:submit.prevent="comment()" class="flex items-center gap-2 w-full mt-2">
        @csrf
        <img src="{{ asset('client/pfp/'.Auth::user()->pfp_url)}}" alt="User Avatar" class="w-12 h-12 rounded-full" />
        <input type="text" wire:model="content" 
            class="flex-grow p-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none"
            placeholder="Comment here!" autocomplete="off" />
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
        <x-primary-button>POST</x-primary-button>
    </form>
</div>