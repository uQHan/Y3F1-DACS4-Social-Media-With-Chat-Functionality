<li class="flex items-center p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md cursor-pointer"
    wire:click="loadUsers({{$user->id}})">
    <img src="{{ asset('client/pfp/'.$user->pfp_url)}}" alt="User Avatar" class="w-10 h-10 rounded-full">
    <div class="ml-3">
        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $user->name }}</p>
        @if ($user->latest_message)
        <p class="text-sm text-gray-600 dark:text-gray-400">{{$user->latest_message}}</p>
        @else
        <p class="text-sm text-gray-600 dark:text-gray-400">&nbsp</p>
        @endif
    </div>
</li>