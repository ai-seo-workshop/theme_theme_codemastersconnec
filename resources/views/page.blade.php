@extends('layout')

@section('title', $pageInfo->seo_title ?? $pageInfo->h1)
@section('description', $pageInfo->seo_desc ?? '')

@section('schema')
    @if(!empty($crumbs))
        <script type="application/ld+json">
            {!! json_encode([
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
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endsection

@section('content')
    <div id="content" class="container-wrapper">
        @include('partials.breadcrumb')
        <section class="section-block-upper single-page-wrap">
            <div id="primary" class="content-area aft-no-sidebar">
                <main id="main" class="site-main">
                    <article class="af-single-article page-article">
                        <header class="entry-header pos-rel">
                            <h1 class="entry-title">{{ $pageInfo->h1 }}</h1>
                            @if($pageInfo->type == \App\Models\MaterielTask::TYPE_CONTACT)
                                <p class="page-contact-desc">{{ \App\Models\MaterielTask::contact_us_desc(app()->getLocale()) }}</p>
                            @endif
                        </header>
                        <div class="entry-content">
                            {!! $pageInfo->content !!}
                        </div>
                    </article>
                </main>
            </div>
        </section>
    </div>
@endsection
