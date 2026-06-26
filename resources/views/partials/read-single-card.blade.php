@php
    $blogItem = $blog ?? null;
    $categoryUrl = $blogItem?->category?->url ?? ($blogItem?->category ? $blogItem->category->url : null);
@endphp
@if($blogItem)
    <article class="af-sec-post latest-posts-grid col-3 float-l pad archive-layout-grid">
        <div class="archive-grid-post">
            <div class="pos-rel read-single color-pad clearfix af-cat-widget-carousel grid-design-default">
                @if(!empty($blogItem->head_img))
                    <div class="read-img pos-rel read-bg-img">
                        <a class="aft-post-image-link" href="{{ $blogItem->url }}" aria-label="{{ $blogItem->title }}">
                            <img
                                src="{{ $blogItem->head_img }}"
                                alt="{{ $blogItem->head_img_alt ?: $blogItem->title }}"
                                loading="lazy"
                                decoding="async"
                            >
                        </a>
                    </div>
                @endif
                <div class="pad read-details color-tp-pad">
                    @if(!empty($blogItem->category_name))
                        <div class="read-categories">
                            <a href="{{ $categoryUrl ?: $blogItem->url }}">{{ $blogItem->category_name }}</a>
                        </div>
                    @endif
                    <p class="read-title">
                        <a href="{{ $blogItem->url }}">{{ $blogItem->title }}</a>
                    </p>
                    @if(!empty($blogItem->summary))
                        <p class="read-summary">{{ \Illuminate\Support\Str::limit(strip_tags($blogItem->summary), 110) }}</p>
                    @endif
                    <a class="read-more-link" href="{{ $blogItem->url }}">
                        {{ \App\Models\MaterielTask::read_article(app()->getLocale()) }}
                    </a>
                </div>
            </div>
        </div>
    </article>
@endif
