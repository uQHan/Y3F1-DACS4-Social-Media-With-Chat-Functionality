@props(['posts'])
<div class="scrolling-pagination space-y-2">
    @foreach($posts as $post)
    <x-post-form :post="$post" />
    @endforeach
    {{ $posts->links() }}
</div>
{{-- Endless Post Scroll --}}
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.scrolling-pagination').jscroll({
            autoTrigger: true,
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.scrolling-pagination',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>