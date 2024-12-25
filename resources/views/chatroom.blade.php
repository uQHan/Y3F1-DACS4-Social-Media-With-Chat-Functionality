<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chatroom') }}
        </h2>
    </x-slot> --}}
    <!-- Main content box -->
    <div>
        <div x-data="{ open: true }" class="relative h-[calc(100vh-4rem)] flex">
            <!-- Sidebar -->
            <div class="flex flex-col">
                <div :class="open ? 'w-72 sm:w-80' : 'w-16'"
                    class="bg-gray-100 dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 h-full transition-all duration-300">
                    <div class="flex items-center justify-between p-4">
                        <h2 x-show="open" class="text-lg font-semibold text-gray-800 dark:text-gray-100">Chats</h2>
                        <button @click="open = !open"
                            class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <hr x-show="open" class="my-4 border-gray-300 dark:border-gray-700">

                    <!-- User List -->
                    <livewire:chat-user-list :users="$users" />
                </div>
            </div>

            <!-- Chat Section -->
            <livewire:chat />
        </div>
    </div>
</x-app-layout>