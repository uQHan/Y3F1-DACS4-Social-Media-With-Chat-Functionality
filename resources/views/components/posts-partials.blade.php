@foreach($posts as $post)
@if ($post->status!='deleted')
<x-post-form :post="$post" />
<div class="py-1"></div>
@endif
@endforeach