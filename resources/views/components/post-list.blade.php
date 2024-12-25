@props(['posts'])

<div x-data="{
    loading: false,
    page: 1,
    noMorePosts: false,
    init() {
        // Add a scroll event listener
        window.addEventListener('scroll', () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 && !this.loading && !this.noMorePosts) {
                this.loadMore();
            }
        });
    },
    async loadMore() {
        this.loading = true;
        try {
            this.page++; // Increment the page number
            const response = await fetch(`/dashboard?page=${this.page}`);
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newPosts = doc.querySelectorAll('.post-form');

            if (newPosts.length === 0) {
                this.noMorePosts = true; // Stop further requests if no posts are returned
            }

            // Append new posts to the scrolling container
            newPosts.forEach(post => {
                document.querySelector('.scrolling-pagination').appendChild(post);
            });
        } catch (error) {
            console.error('Error loading posts:', error);
        } finally {
            this.loading = false;
        }
    }
}" x-init="init()">
    <div class="scrolling-pagination">
        <!-- Existing Posts -->
        @foreach($posts as $post)
        @if ($post->status!='deleted')
        <div class="post-form">
            <x-post-form :post="$post" />
            <div class="py-1"></div>
        </div>
        @endif
        @endforeach
    </div>

    <!-- Loading Spinner -->
    <div x-show="loading" class="text-center py-4">
        <span>Loading...</span>
    </div>

    <!-- Load More Button (optional, can be replaced with auto-scroll) -->
    <div x-show="!loading && !noMorePosts">
        <button @click="loadMore" class="w-full py-2 mt-4 bg-blue-500 text-white rounded">
            Load More
        </button>
    </div>
</div>