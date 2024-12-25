<div class="flex-1 bg-white dark:bg-gray-900 flex flex-col">
    @if ($userId)
    <!-- Chat Header -->
    <div class="flex items-center justify-between border-b border-gray-300 dark:border-gray-700 p-4">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Chat with User {{ $userId }}</h3>
    </div>

    <!-- Chat Messages -->
    <div x-data="{ scrollToBottom() { $refs.messagesContainer.scrollTop = $refs.messagesContainer.scrollHeight; } }"
        x-init="scrollToBottom()" 
        x-on:messageSent.window="scrollToBottom()"
        x-ref="messagesContainer" 
        class="flex-1 mt-4 space-y-4 overflow-y-auto p-4">
        @foreach ($history as $message)
        @if($message->creator_id == Auth::id())
        <div class="flex justify-end">
            <p class="break-all bg-blue-500 text-white p-3 rounded-lg max-w-xs">
                {{ $message->content }}
            </p>
        </div>
        @else
        <div class="flex flex-col">
            <strong class="text-black dark:text-gray-200 ms-1">{{ $message->creator->name }}</strong>
            <div class="flex">
                <p
                    class="break-all bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 p-3 rounded-lg max-w-xs">
                    {{ $message->content }}
                </p>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    {{-- Chat Input --}}
    <div class="mt-4 flex items-end p-4 self-end w-full">
        <div class="flex-1 flex flex-col self-end">
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
            <input type="text" placeholder="Write a message here..." wire:model="content" wire:keydown.enter="sendMessage()"
                class="flex-1 px-4 py-2 border rounded-lg border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100 bg-gray-100 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">

        </div>
        <button wire:click="sendMessage()"
            class="ml-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg focus:outline-none self-end">
            Send
        </button>
    </div>
    @else
    <div class="flex-1 flex items-center justify-center p-4 gap-2">
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-300">Select a user to start chatting.</h3>
            <h1>
                <i class="far fa-envelope fa-10x text-gray-700"></i>
            </h1>
        </div>
    </div>
    @endif
</div>