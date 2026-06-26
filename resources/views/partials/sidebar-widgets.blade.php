@php
    $widgetPosts = $posts ?? collect();
    $widgetTitle = $title ?? \App\Models\MaterielTask::popular_articles(app()->getLocale());
@endphp
<div id="secondary" class="sidebar-area sidebar-sticky-top">
    <aside class="widget-area color-pad">
        <div class="widget darknews-widget darknews_posts_lists_widget">
            <p class="widget-title header-after1 category-color-1">{{ $widgetTitle }}</p>
            <div class="full-wid-resp af-widget-body af-container-row clearfix">
                @forelse($widgetPosts as $post)
                    <article class="widget-post-item">
                        @if(!empty($post->head_img))
                            <a class="widget-post-thumb" href="{{ $post->url }}">
                                <img
                                    src="{{ $post->head_img }}"
                                    alt="{{ $post->head_img_alt ?: $post->title }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>
                        @endif
                        <div class="widget-post-content">
                            <a class="widget-post-title" href="{{ $post->url }}">{{ $post->title }}</a>
                            @if(!empty($post->published_at))
                                <span class="widget-post-date">{{ $post->published_at->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="widget-empty">-</p>
                @endforelse
            </div>
        </div>
    </aside>
</div>
