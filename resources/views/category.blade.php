@extends('layout')

@section('title', $seoInfo->seo_title ?? $categoryInfo->name)
@section('description', $seoInfo->seo_desc ?? '')

@section('schema')
    @php
        $collectionSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $seoInfo->seo_title ?? $categoryInfo->name,
            'url' => rtrim(url()->current(), '/') . '/',
            'description' => $seoInfo->seo_desc ?? '',
        ];
        $breadcrumbSchema = null;
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
    @endphp
    <script type="application/ld+json">{!! json_encode($collectionSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @if($breadcrumbSchema)
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
@endsection

@section('content')
    @php
        $sidebarPosts = ($hotPosts ?? $hotBlogs ?? collect())->take(4);
        if ($sidebarPosts->isEmpty() && isset($blogs)) {
            $sidebarPosts = collect($blogs->items())->take(4);
        }
    @endphp
    <div id="content" class="container-wrapper">
        @include('partials.breadcrumb')
        <div class="section-block-upper">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <header class="header-title-wrapper1 entry-header-details">
                        <h1 class="page-title">{{ $categoryInfo->name }}</h1>
                    </header>
                    @include('partials.article-list', ['blogs' => $blogs])
                    @include('partials.pagination', ['paginator' => $blogs])
                </main>
            </div>

            @include('partials.sidebar-widgets', [
                'title' => \App\Models\MaterielTask::hot_topics(app()->getLocale()),
                'posts' => $sidebarPosts
            ])
        </div>
    </div>
@endsection
