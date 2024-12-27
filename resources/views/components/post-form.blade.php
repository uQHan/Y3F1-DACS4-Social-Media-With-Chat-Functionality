@props(['post'])
@php
$creator = $post->creator;
@endphp
<div
  class="relative w-full mx-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 sm:rounded-lg shadow-md p-4">
  <div class="flex items-start justify-between">
    <!-- Avatar -->
    <div class="flex items-start gap-2 flex-1">
      <a href={{ route('profile.other', $creator->id) }} >
        <img src={{ asset('client/pfp/'.$creator->pfp_url)}} alt="User Avatar" class="w-12 h-12 rounded-full" />
      </a>

      <!-- Content -->
      <div x-data="{commentsVisible: false}" class="flex-1">
        <!-- Header -->
        <div class="flex flex-col items-start">
          <a href={{ route('profile.other', $creator->id) }} >
            <span class="font-bold text-gray-900 dark:text-gray-100">{{ $creator->name }}</span>
            <div class="flex items-center gap-2">
              <span
                class="text-gray-500 dark:text-gray-400">{{\Carbon\Carbon::parse($post->created_at)->format('d/m/Y')}}</span>
              <span class="text-gray-500 dark:text-gray-400">&middot;</span>
              <span class="text-gray-500 dark:text-gray-400 text-sm">{{$post->created_at->diffForHumans(null, false ,
                true)}}</span>
            </div>
          </a>
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
            commentCount: {{ $post->comments->count() }}, 
          }">
          <div class="flex justify-statrt space-x-8">
            <!-- Like Button -->
            <div>
              <livewire:interactive-button :item="$post" type="like" :key="$post->id" />
            </div>


            <!-- Comment Button -->
            <button @click="commentsVisible = !commentsVisible" :class="commentsVisible ? 'text-gray-500' : ''"
              class="flex items-center">
              <i :class="commentsVisible ? 'fas fa-comment' : 'far fa-comment'"></i>
              <span class="ml-2">{{ $post->comments->count() }}</span>
            </button>

          </div>

          <!-- Bookmark Button -->
          <div>
            <livewire:interactive-button :item="$post" type="bookmark" :key="$post->id" />
          </div>
        </div>

        <!-- Comment List -->
        <div x-show="commentsVisible" x-transition class="mt-4 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
          <livewire:comment-box :post_id="$post->id" :key="$post->id" />
        </div>
      </div>
    </div>

    <!-- Right: Dropdown Menu -->
    <div class="relative" x-data="{ menuVisible: false }">
      <!-- Dropdown Toggle -->
      <button @click="menuVisible = !menuVisible"
        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none">
        <i class="fas fa-ellipsis-h"></i>
      </button>

      <!-- Dropdown Menu -->
      <div x-show="menuVisible" x-transition
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-300 dark:border-gray-600"
        @click.away="menuVisible = false">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
          @if ($post->creator_id == Auth::user()->id)
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
              <i class="text-gray-500 dark:text-gray-400 fas fa-pen mr-2"></i>Edit Post</a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600"
              x-on:click.prevent="$dispatch('open-modal', 'confirm-post-{{$post->id}}-deletion')">
              <i class="text-gray-500 dark:text-gray-400 fas fa-trash mr-2"></i>Delete Post</a>
          </li>
          @endif
          <li>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">
              <i class="text-gray-500 dark:text-gray-400 fas fa-flag mr-2"></i>Report</a>
          </li>
        </ul>
      </div>

      {{-- Confirm deletion modal --}}
      <x-modal name="confirm-post-{{$post->id}}-deletion" :show="$errors->postDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('post.destroy') }}" class="p-6">
          @csrf
          @method('delete')
          <input type="hidden" name="post_id" value="{{$post->id}}">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Are you sure you want to delete your post?') }}
          </h2>

          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your Post is deleted, It will not show up to others, but it will still remain in our database.')
            }}
          </p>

          <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
              {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3">
              {{ __('Delete Post') }}
            </x-danger-button>
          </div>
        </form>
      </x-modal>
    </div>
  </div>
</div>