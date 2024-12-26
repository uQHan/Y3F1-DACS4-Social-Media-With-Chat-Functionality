@php
$user = Auth::user();
@endphp
<div x-data="{
        isExpanded: @if($errors->any()) true @else false @endif,
        title: '',
        content: '',
        image: null,
        isMinimizable() {
            return !this.title && !this.content && !this.image;
        },
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    this.image = reader.result;
                };
                reader.readAsDataURL(file);
            }
        },
        clearImagePreview() {
            this.image = null;
        }
    }"
    class="relative w-full mx-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 sm:rounded-lg sm:shadow-md p-4 min-w-[400px] mb-2">
    
    <!-- Initial Minimized View -->
    <div x-show="!isExpanded" id="collapsedPostBox" class="cursor-pointer" @click="isExpanded = true">
        <div class="flex items-center gap-2">
            <img src="{{ asset('client/pfp/'.$user->pfp_url)}}" alt="User Avatar" class="w-12 h-12 rounded-full" />
            <input type="text" id="postTitleCollapsed"
                class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none"
                placeholder="What's on your mind?" readonly />
        </div>
    </div>

    <!-- Full Expanded Form -->
    <form x-show="isExpanded" id="expandedPostBox" class="mt-4" method="POST" action="{{ route('post.post') }}" enctype="multipart/form-data" @focusout="if (isMinimizable()) isExpanded = false">
        @csrf
        <!-- Header: User Info -->
        <div class="flex items-start gap-2">
            <img src="{{ asset('client/pfp/'.$user->pfp_url)}}" alt="User Avatar" class="w-12 h-12 rounded-full" />
            <div class="flex flex-col w-full">
                <span class="font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</span>

                <!-- Title Input for Post -->
                <input type="text" name="title" x-model="title"
                    class="w-full p-2 mt-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter post title" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />

                <!-- Post Type Dropdown -->
                <select name="type" id="postType"
                    class="w-full p-2 mt-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="normal" selected>Normal Post</option>
                    {{-- <option value="announcement">Announcement</option>
                    <option value="question">Question</option> --}}
                    <option value="event">Event</option>
                </select>
                <x-input-error :messages="$errors->get('type')" class="mt-2" />

                <!-- Textarea for Post Content -->
                <textarea id="postContent" name="content" x-model="content"
                    class="w-full p-2 mt-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4" placeholder="What's on your mind?" required></textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />

                <!-- Image Upload and Post Button Container -->
                <div class="flex items-center justify-between mt-3">
                    <!-- Add Image Button -->
                    <button type="button" @click="$refs.imageUpload.click()"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <i class="fas fa-camera"></i> Add Image
                    </button>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />

                    <!-- Post Button -->
                    <x-primary-button type="submit">
                        Post
                    </x-primary-button>

                    <!-- Hidden Image Input -->
                    <input type="file" x-ref="imageUpload" id="imageUpload" name="image" class="hidden" accept="image/*" @change="previewImage" />
                </div>

                <!-- Image Preview -->
                <div x-show="image" id="imagePreviewContainer" class="mt-4">
                    <img :src="image" alt="Image Preview" class="w-full rounded-lg shadow-md" />
                    <button type="button" class="text-red-500 mt-2 hover:text-red-700" @click="clearImagePreview">
                        Remove Image
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
