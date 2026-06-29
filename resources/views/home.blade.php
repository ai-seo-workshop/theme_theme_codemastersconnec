@extends('layout')

@section('title', $seoInfo->seo_title ?? config('app.name'))
@section('description', $seoInfo->seo_desc ?? '')

@section('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => rtrim(url('/'), '/') . '/',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
    @php
        $hotPosts = ($hotPosts ?? $hotBlogs ?? collect())->values();
        $latestPosts = ($latestPosts ?? $latestBlogs ?? collect())->values();
        $usedIds = $hotPosts->pluck('id')->filter()->all();
        $latestPosts = $latestPosts->reject(fn($post) => in_array($post->id, $usedIds, true))->values();
    @endphp

    @include('partials.banner-carousel', ['posts' => $hotPosts])

    <div id="content" class="container-wrapper">
        <section class="section-block-upper">
            <div id="primary" class="content-area">
                <header class="header-title-wrapper1 entry-header-details">
                    <h1 class="page-title">{{ $seoInfo->h1 ?: \App\Models\MaterielTask::homeH1(app()->getLocale()) }}</h1>
                    @if(!empty($seoInfo->slogan))
                        <p class="page-subtitle">{{ $seoInfo->slogan }}</p>
                    @endif
                </header>

                @if($latestPosts->isNotEmpty())
                    <section class="homepage-block">
                        <h2 class="widget-title header-after1 category-color-1">{{ \App\Models\MaterielTask::recent_posts(app()->getLocale()) }}</h2>
                        <div class="full-wid-resp af-widget-body">
                            <div class="af-container-row aft-archive-wrapper darknews-customizer clearfix archive-layout-grid two-col-masonry">
                                @foreach($latestPosts as $blog)
                                    @include('partials.read-single-card', ['blog' => $blog])
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                @if(!empty($blogs) && !empty($categories))
                    @foreach($categories as $category)
                        @php
                            $categoryPosts = $blogs->get($category->id, collect())->values();
                        @endphp
                        @if($categoryPosts->isNotEmpty())
                            <section class="homepage-block">
                                <h2 class="widget-title header-after1 category-color-1">
                                    <a href="{{ $category->url }}">{{ $category->name }}</a>
                                </h2>
                                <div class="full-wid-resp af-widget-body">
                                    <div class="af-container-row aft-archive-wrapper darknews-customizer clearfix archive-layout-grid two-col-masonry">
                                        @foreach($categoryPosts as $blog)
                                            @include('partials.read-single-card', ['blog' => $blog])
                                        @endforeach
                                    </div>
                                </div>
                            </section>
                        @endif
                    @endforeach
                @endif

                @if(!empty($seoInfo->content))
                    <section class="homepage-copy color-pad">
                        {!! $seoInfo->content !!}
                    </section>
                @endif
            </div>

            @include('partials.sidebar-widgets', [
                'title' => \App\Models\MaterielTask::hot_topics(app()->getLocale()),
                'posts' => $hotPosts->take(4)
            ])
        </section>
    </div>
@endsection
