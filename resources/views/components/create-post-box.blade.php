<div id="createPostContainer"
    class="relative max-w-xl mx-auto bg-white dark:bg-gray-800 border sm:border-0 sm:border-gray-300 dark:sm:border-gray-700 sm:rounded-lg shadow-md p-4 min-w-[400px]">
    <!-- Initial Minimized View -->
    <div id="collapsedPostBox" class="cursor-pointer" onclick="expandPostBox()">
        <div class="flex items-center gap-2">
            <img src="https://via.placeholder.com/50" alt="User Avatar" class="w-12 h-12 rounded-full" />
            <input type="text" id="postTitleCollapsed"
                class="w-full p-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg focus:outline-none"
                placeholder="What's on your mind?" readonly />
        </div>
    </div>

    <!-- Full Expanded Form -->
    <form id="expandedPostBox" class="hidden mt-4" method="POST" action="{{ route('post.post')}}"
        enctype="multipart/form-data" onfocusout="checkFocusOut(event)">
        @csrf
        <!-- Header: User Info -->
        <div class="flex items-start gap-2">
            <img src="https://via.placeholder.com/50" alt="User Avatar" class="w-12 h-12 rounded-full" />
            <div class="flex flex-col w-full">
                <span class="font-bold text-gray-900 dark:text-gray-100">John Doe</span>

                <!-- Title Input for Post -->
                <input type="text" name="title" id="title"
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
                <textarea id="postContent" name="content"
                    class="w-full p-2 mt-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4" placeholder="What's on your mind?" required></textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />

                <!-- Image Upload and Post Button Container -->
                <div class="flex items-center justify-between mt-3">
                    <!-- Add Image Button -->
                    <button type="button" onclick="document.getElementById('imageUpload').click()"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <i class="fas fa-camera"></i> Add Image
                    </button>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />

                    <!-- Post Button -->
                    <x-primary-button type="submit">
                        Post
                    </x-primary-button>

                    <!-- Hidden Image Input -->
                    <input type="file" id="imageUpload" name="image" class="hidden" accept="image/*"
                        onchange="previewImage()" />
                </div>

                <!-- Image Preview -->
                <div id="imagePreviewContainer" class="mt-4 hidden">
                    <img id="imagePreview" src="" alt="Image Preview" class="w-full rounded-lg shadow-md" />
                    <button type="button" class="text-red-500 mt-2 hover:text-red-700" onclick="clearImagePreview()">
                        Remove Image
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
    // <!-- JavaScript for Box Sizing -->
    function expandPostBox() {
    document.getElementById('collapsedPostBox').classList.add('hidden');
    document.getElementById('expandedPostBox').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById("title").focus();
    }, 0);
    }

    function checkFocusOut(event) {
    const expandedPostBox = document.getElementById("expandedPostBox");
    if (!expandedPostBox.contains(event.relatedTarget)) {
      minimizePostBox();
    }
    }

    function minimizePostBox() {
    document.getElementById("collapsedPostBox").classList.remove("hidden");
    document.getElementById("expandedPostBox").classList.add("hidden");
    }

    // <!-- JavaScript for Image Preview -->
    function previewImage() {
      const fileInput = document.getElementById('imageUpload');
      const file = fileInput.files[0];
      const previewContainer = document.getElementById('imagePreviewContainer');
      const previewImage = document.getElementById('imagePreview');
  
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewImage.src = e.target.result; // Set image source
          previewContainer.classList.remove('hidden'); // Show the preview container
        };
        reader.readAsDataURL(file); // Convert image to data URL
      }
    }
  
    function clearImagePreview() {
      const fileInput = document.getElementById('imageUpload');
      const previewContainer = document.getElementById('imagePreviewContainer');
      const previewImage = document.getElementById('imagePreview');
  
      fileInput.value = ''; // Clear the file input
      previewImage.src = ''; // Clear the preview image source
      previewContainer.classList.add('hidden'); // Hide the preview container
    }
</script>