@props(['post'])
@php
$user = Auth::user($post->creator_id);
$creator_name = $user->name;
@endphp
<div
  class="relative max-w-xl mx-auto bg-white dark:bg-gray-800 border sm:border-gray-300 dark:sm:border-gray-700 sm:rounded-lg shadow-md p-4">
  <div class="flex items-start justify-between">
    <!-- Avatar -->
    <div class="flex items-start gap-2 flex-1">
      <img src="https://via.placeholder.com/50" alt="User Avatar" class="w-12 h-12 rounded-full" />

      <!-- Content -->
      <div x-data="{commentsVisible: false}" class="flex-1">
        <!-- Header -->
        <div class="flex flex-col items-start">
          <span class="font-bold text-gray-900 dark:text-gray-100">{{ $creator_name }}</span>
          <div class="flex items-center gap-2">
            <span
              class="text-gray-500 dark:text-gray-400">{{\Carbon\Carbon::parse($post->created_at)->format('d/m/Y')}}</span>
            <span class="text-gray-500 dark:text-gray-400">&middot;</span>
            <span class="text-gray-500 dark:text-gray-400 text-sm">{{$post->created_at->diffForHumans(null, false ,
              true)}}</span>
          </div>
        </div>

        <!-- Post Title -->
        <p class="text-gray-800 dark:text-gray-200 font-medium mt-1 overflow-hidden">
          {{$post->title}}
        </p>

        <!-- Post Content -->
        <p class="text-gray-800 dark:text-gray-200 mt-1 overflow-hidden">
          {{$post->content}}
        </p>

        <!-- Image -->
        @if ($post->image_url)
        <img src="{{ asset('client/image/'. $post->image_url)}}" alt="Post Image"
          class="w-full mt-3 rounded-lg shadow-md" />
        @endif

        <!-- Interactions -->
        <div class="flex justify-between items-center text-gray-500 dark:text-gray-400 mt-4 space-x-8" x-data="{ 
            bookmarked: false, 
            commentCount: {{ $post->comments->count() }}, 
            bookmarkCount: {{ $post->bookmarks->count() }} 
          }">
          <div class="flex justify-statrt space-x-8">
            <!-- Like Button -->
            <div>
              <livewire:interactive-button :item="$post" type="like" />
            </div>


            <!-- Comment Button -->
            <button @click="commentsVisible = !commentsVisible" :class="commentsVisible ? 'text-gray-500' : ''"
              class="flex items-center">
              <i :class="commentsVisible ? 'fas fa-comment' : 'far fa-comment'"></i>
              <span class="ml-2">{{ $post->comments->count() }}</span>
            </button>

          </div>

          <!-- Bookmark Button -->
          <button @click="bookmarked = !bookmarked; bookmarked ? bookmarkCount++ : bookmarkCount--"
            :class="bookmarked ? 'text-blue-500 dark:text-blue-400' : ''"
            class="flex items-center hover:text-blue-500 dark:hover:text-blue-400">
            <i class="far fa-bookmark" :class="bookmarked ? 'fas fa-bookmark' : ''"></i>
            <span class="ml-2" x-text="bookmarkCount"></span>
          </button>
        </div>

        <!-- Comment List -->
        <div x-show="commentsVisible" x-transition class="mt-4 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
          <ul class="space-y-2 gap-2">
            @forelse($post->comments as $comment)
            <li class="flex items-start gap-2">
              <img src="https://via.placeholder.com/40" alt="User Avatar" class="w-10 h-10 rounded-full">
              <div>
                <span class="block font-bold text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</span>
                <p class="text-sm text-gray-800 dark:text-gray-300">{{ $comment->content }}</p>
              </div>
            </li>
            @empty
            <li class="text-gray-600 dark:text-gray-400">No comments yet.</li>
            @endforelse
          </ul>
          <!-- Create Comment Box -->
          <form action="{{ route('post.comment',$post->id)}}" method="POST" class="flex items-center gap-2 w-full mt-2">
            @csrf
            <img src="https://via.placeholder.com/50" alt="User Avatar" class="w-12 h-12 rounded-full" />
            <input type="text" id="postTitleCollapsed" name="content"
              class="flex-grow p-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none"
              placeholder="Comment here!" autocomplete="off" />
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
            <x-primary-button>POST</x-primary-button>
          </form>
        </div>
      </div>
    </div>

    <!-- Right: Dropdown Menu -->
    <div class="relative">
      <!-- Dropdown Toggle -->
      <button id="dropdownToggle" onclick="toggleDropdown()"
        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none">
        <i class="fas fa-ellipsis-h"></i>
      </button>

      <!-- Dropdown Menu -->
      <div id="dropdownMenu"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-300 dark:border-gray-600 hidden">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">

          @if ($post->creator_id == Auth::user()->id)
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Edit Post</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Delete Post</a>
          </li>
          @endif

          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Report</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
{{-- Dropdown Menu Script --}}
<script>
  function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
  }

  // Close dropdown if clicking outside
  document.addEventListener('click', (e) => {
    const toggle = document.getElementById('dropdownToggle');
    const menu = document.getElementById('dropdownMenu');
    if (!toggle.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.add('hidden');
    }
  });
</script>