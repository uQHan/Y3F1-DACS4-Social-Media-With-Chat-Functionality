<div>
    <div>
        <ul x-show="open" x-cloak class="space-y-2 p-4">
            @foreach ($users as $user)
            <livewire:chat-list-item :user="$user" :key="$user->id" />
            @endforeach
        </ul>
    </div>
</div>