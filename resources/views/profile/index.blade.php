<x-app-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           {{ __('Profile') }}
       </h2>
   </x-slot>

   <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- User Info Section (Avatar, Bio, Address, Link) -->
            <div class="p-4 bg-black dark:bg-gray-800 shadow-md sm:rounded-lg lg:col-span-3">
                <!-- User Avatar -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('client/pfp/'.$user->pfp_url) }}" alt="User Avatar" class="w-20 h-20 rounded-full border-4 border-white">
                    <div class="text-white">
                        <h2 class="text-2xl font-semibold">{{ $user->name }}</h2>
                        <p class="text-gray-300">{{ $user->bio }}</p>
                    </div>
                </div>
                <hr class="my-4 border-gray-300 dark:border-gray-700">

                <!-- User Address -->
                <div class="mt-4">
                    <p class="text-gray-400">Address: {{ $user->address ?? 'Not provided' }}</p>
                </div>

                <!-- User Website or Link -->
                <div class="mt-4">
                    @if ($user->website)
                        <a href="{{ $user->website }}" target="_blank" class="text-blue-500 hover:underline">
                            Visit Website
                        </a>
                    @else
                        <p class="text-gray-400">No website available</p>
                    @endif
                </div>
            </div>

            <!-- Main Content (Posts) -->
            <div class="p-0 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg lg:col-span-2">
                <div class="w-full">
                    <x-create-post-box />
                    <x-post-list :posts="$posts" route="/profile"/>
                </div>
            </div>

            <!-- Sidebar: Tech Explanation Card (on the right side) -->
            <div class="p-4 bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">App Features</h3>
                <div class="space-y-4 mt-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-comment-alt text-gray-700 dark:text-gray-300"></i>
                        <p class="text-gray-700 dark:text-gray-300">Chat: Engage in real-time messaging with friends and other users.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-pencil-alt text-gray-700 dark:text-gray-300"></i>
                        <p class="text-gray-700 dark:text-gray-300">Post: Share thoughts, media, and ideas with your followers.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-comment-dots text-gray-700 dark:text-gray-300"></i>
                        <p class="text-gray-700 dark:text-gray-300">Comments: Comment on posts to engage with other users' content.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-thumbs-up text-gray-700 dark:text-gray-300"></i>
                        <p class="text-gray-700 dark:text-gray-300">Post Interaction: Like, share, and interact with posts in various ways.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-users text-gray-700 dark:text-gray-300"></i>
                        <p class="text-gray-700 dark:text-gray-300">User Profiles: View and interact with other users' profiles.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>