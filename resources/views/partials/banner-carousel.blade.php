@php
    $heroPosts = ($posts ?? collect())->values();
    $featured = $heroPosts->first();
    $secondary = $heroPosts->slice(1, 4);
@endphp
@if($featured)
    <section class="aft-blocks aft-main-banner-section banner-carousel-1-wrap bg-fixed darknews-customizer aft-banner-layout-4 aft-banner-order-1">
        <div class="container-wrapper">
            <div class="aft-main-banner-wrapper">
                <div class="aft-main-banner-part af-container-row-5">
                    <div class="aft-slider-part col-2 pad-5">
                        <div class="hero-feature-card pos-rel read-single color-pad clearfix af-cat-widget-carousel grid-design-texts-over-image">
                            @if(!empty($featured->head_img))
                                <a class="aft-post-image-link" href="{{ $featured->url }}">
                                    <img src="{{ $featured->head_img }}" alt="{{ $featured->head_img_alt ?: $featured->title }}" loading="lazy" decoding="async">
                                </a>
                            @endif
                            <div class="pad read-details color-tp-pad">
                                <div class="read-categories">
                                    <a href="{{ optional($featured->category)->url ?? $featured->url }}">{{ $featured->category_name }}</a>
                                </div>
                                <p class="read-title"><a href="{{ $featured->url }}">{{ $featured->title }}</a></p>
                                @if(!empty($featured->summary))
                                    <p class="read-summary">{{ \Illuminate\Support\Str::limit(strip_tags($featured->summary), 140) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="aft-trending-part col-2 pad-5">
                        @foreach($secondary as $post)
                            <article class="hero-side-item pos-rel read-single color-pad clearfix">
                                @if(!empty($post->head_img))
                                    <a class="hero-side-thumb" href="{{ $post->url }}">
                                        <img src="{{ $post->head_img }}" alt="{{ $post->head_img_alt ?: $post->title }}" loading="lazy" decoding="async">
                                    </a>
                                @endif
                                <div class="hero-side-content">
                                    <p class="hero-side-title"><a href="{{ $post->url }}">{{ $post->title }}</a></p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
