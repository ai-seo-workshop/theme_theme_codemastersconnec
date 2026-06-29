@extends('layout')

@section('title', $blog->title)
@section('description', $blog->summary ?? '')

@section('schema')
    @php
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => strip_tags($blog->title),
            'description' => strip_tags($blog->summary ?? ''),
            'author' => [
                '@type' => 'Person',
                'name' => $blog->author ?: config('app.name'),
            ],
            'datePublished' => optional($blog->published_at)->toIso8601String(),
            'dateModified' => optional($blog->update_time ?? $blog->published_at)->toIso8601String(),
            'mainEntityOfPage' => $blog->absoluteUrl(),
            'url' => $blog->absoluteUrl(),
            'image' => [],
        ];
        if (!empty($crumbs)) {
            $breadcrumbSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($crumbs)->values()->map(function ($crumb, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $crumb['title'],
                        'item' => $crumb['absolute_url'],
                    ];
                })->all(),
            ];
        }
        $faqItems = collect($blog->faq ?? [])->filter(fn($f) => !empty($f['question']) && !empty($f['answer']));
        $faqSchema = null;
        if ($faqItems->isNotEmpty()) {
            $faqSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqItems->map(function ($item) {
                    return [
                        '@type' => 'Question',
                        'name' => strip_tags($item['question']),
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => trim(strip_tags($item['answer'])),
                        ],
                    ];
                })->values()->all(),
            ];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @if(!empty($breadcrumbSchema))
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
    @if($faqSchema)
        <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
@endsection

@section('content')
    @php
        $popularPosts = ($popularPosts ?? $popularBlogs ?? collect())->take(4);
        $relatedPosts = ($relatedBlogs ?? collect())->take(3);
        $hasH1Tag = stripos((string) $blog->h1, '<h1') !== false;
    @endphp
    <div id="content" class="container-wrapper">
        @include('partials.breadcrumb')
        <section class="section-block-upper">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <article class="af-single-article">
                        <div class="entry-content-wrap read-single social-after-title">
                            <header class="entry-header pos-rel">
                                <div class="entry-header-details af-cat-widget-carousel">
                                    @if(!empty($blog->category_name))
                                        <div class="figure-categories read-categories figure-categories-bg">
                                            <a href="{{ optional($blog->category)->url ?? $blog->url }}">{{ $blog->category_name }}</a>
                                        </div>
                                    @endif
                                    @if($hasH1Tag)
                                        {!! $blog->h1 !!}
                                    @else
                                        <h1 class="entry-title">{!! $blog->h1 !!}</h1>
                                    @endif
                                    <div class="entry-meta">
                                        <span>{{ \App\Models\MaterielTask::detailPublished(app()->getLocale()) }}: {{ optional($blog->published_at)->format('M d, Y') }}</span>
                                        <span>{{ \App\Models\MaterielTask::by(app()->getLocale()) }} {{ $blog->author ?: config('app.name') }}</span>
                                        @if(!empty($blog->category_name))
                                            <span>{{ \App\Models\MaterielTask::filedUnder(app()->getLocale()) }} {{ $blog->category_name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </header>

                            <div class="entry-content">
                                {!! $blog->content !!}
                            </div>

                            @if($faqItems->isNotEmpty())
                                <section class="faq-block">
                                    <h2>{{ \App\Models\MaterielTask::detail_content(app()->getLocale()) }}</h2>
                                    @foreach($faqItems as $item)
                                        <article class="faq-item">
                                            <h3>
                                                <button class="faq-toggle" type="button" aria-expanded="false">{{ $item['question'] }}</button>
                                            </h3>
                                            <div class="faq-answer" hidden>
                                                {!! $item['answer'] !!}
                                            </div>
                                        </article>
                                    @endforeach
                                </section>
                            @endif

                            @if($relatedPosts->isNotEmpty())
                                <section class="related-posts">
                                    <p class="widget-title">{{ \App\Models\MaterielTask::related_posts(app()->getLocale()) }}</p>
                                    <div class="related-grid">
                                        @foreach($relatedPosts as $post)
                                            <article class="related-item">
                                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                                            </article>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        </div>
                    </article>
                </main>
            </div>

            @include('partials.sidebar-widgets', [
                'title' => \App\Models\MaterielTask::popular_articles(app()->getLocale()),
                'posts' => $popularPosts
            ])
        </section>
    </div>
@endsection
