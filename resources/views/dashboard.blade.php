<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="flex flex-col lg:flex-row items-start">

      <!-- Main content box -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full lg:w-2/3">
        <div class="block p-6 space-y-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg grow">
          <!-- Create Post Box -->
          <x-create-post-box />
          <x-post-list :posts="$posts" />
        </div>
      </div>
    </div>
  </div>
</x-app-layout>