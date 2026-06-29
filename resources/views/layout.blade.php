<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $seoInfo->seo_title ?? $pageInfo->seo_title ?? ($blog->title ?? config('app.name')))</title>
    <meta name="description" content="@yield('description', $seoInfo->seo_desc ?? $pageInfo->seo_desc ?? ($blog->summary ?? ''))">
    <link rel="canonical" href="@yield('canonical', rtrim(url()->current(), '/') . '/')">
    {!! $alternate_tag ?? '' !!}
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style_598f9e85.css') }}">
    @stack('styles')
    @yield('schema')
    @if(!empty($gtag))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtag }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gtag }}');
        </script>
    @endif
</head>
<body class="aft-dark-mode @yield('body_class')">
@php
    $locale = app()->getLocale();
    $defaultLocale = config('app.default_language', 'en');
    $homeUrl = $locale === $defaultLocale
        ? route_slash('home')
        : (\Illuminate\Support\Facades\Route::has('home.localized') ? route_slash('home.localized', ['locale' => $locale]) : url('/' . $locale . '/'));
    $supports = \App\Models\MaterielTask::SUPPORTS($locale);
    $supportUrl = function (array $item) use ($locale, $defaultLocale) {
        $uri = $item['uri'] ?? '';
        if ($locale === $defaultLocale) {
            return route_slash($uri);
        }
        $localizedName = $uri . '.localized';
        if (\Illuminate\Support\Facades\Route::has($localizedName)) {
            return route_slash($localizedName, ['locale' => $locale]);
        }
        return route_slash($uri);
    };
    $smallWords = ['of', 'in', 'for', 'the', 'and', 'or', 'to', 'a', 'an'];
    $titleCase = function ($label) use ($smallWords) {
        $parts = preg_split('/\s+/', trim((string) $label));
        return collect($parts)->map(function ($word, $index) use ($smallWords) {
            $lower = mb_strtolower($word);
            if ($index > 0 && in_array($lower, $smallWords, true)) {
                return $lower;
            }
            return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');
        })->implode(' ');
    };
    $siteName = config('app.name');
@endphp
<div id="page" class="site af-whole-wrapper">
    <a class="skip-link screen-reader-text" href="#content">{{ \App\Models\MaterielTask::home($locale) }}</a>
    <header id="masthead" class="header-layout-default darknews-header">
        <div class="af-middle-header">
            <div class="container-wrapper">
                <div class="af-middle-container">
                    <div class="logo">
                        <div class="site-branding">
                            <a href="{{ $homeUrl }}" class="custom-logo-link" rel="home">
                                <img src="{{ $logoUrl }}" class="custom-logo" alt="{{ $siteName }}" loading="eager" decoding="async">
                            </a>
                            @if(!empty($slogan->slogan ?? null))
                                <p class="site-description">{{ $slogan->slogan }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="main-navigation-bar" class="af-bottom-header">
            <div class="container-wrapper">
                <div class="bottom-bar-flex">
                    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" data-menu-toggle>
                        <span class="ham"></span>
                        <span class="sr-only">Menu</span>
                    </button>
                    <nav class="main-navigation clearfix" aria-label="Primary Navigation">
                        <div class="menu main-menu menu-desktop show-menu-border" data-menu-panel>
                            <ul id="primary-menu" class="menu">
                                <li><a href="{{ $homeUrl }}">{{ $titleCase(\App\Models\MaterielTask::home($locale)) }}</a></li>
                                @foreach($categories ?? [] as $category)
                                    <li><a href="{{ $category->url }}">{{ $titleCase($category->name) }}</a></li>
                                @endforeach
                                @foreach($supports as $item)
                                    <li><a href="{{ $supportUrl($item) }}">{{ $titleCase($item['name']) }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <footer class="site-footer aft-footer-sidebar-col-1">
        <div class="primary-footer">
            <div class="container-wrapper footer-grid">
                <section class="footer-column">
                    <p class="widget-title">{{ \App\Models\MaterielTask::company($locale) }}</p>
                    <ul>
                        @foreach($supports as $item)
                            @if(!in_array($item['uri'], ['privacy', 'terms'], true))
                                <li><a href="{{ $supportUrl($item) }}">{{ $titleCase($item['name']) }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </section>
                <section class="footer-column">
                    <p class="widget-title">{{ \App\Models\MaterielTask::resource($locale) }}</p>
                    <ul>
                        @foreach($categories ?? [] as $category)
                            <li><a href="{{ $category->url }}">{{ $titleCase($category->name) }}</a></li>
                        @endforeach
                    </ul>
                </section>
                <section class="footer-column">
                    <p class="widget-title">{{ \App\Models\MaterielTask::legal($locale) }}</p>
                    <ul>
                        @foreach($supports as $item)
                            @if(in_array($item['uri'], ['privacy', 'terms'], true))
                                <li><a href="{{ $supportUrl($item) }}">{{ $titleCase($item['name']) }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </section>
            </div>
        </div>
        <div class="secondary-footer">
            <div class="container-wrapper">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ \App\Models\MaterielTask::copyright($locale) }}</p>
            </div>
        </div>
    </footer>
</div>
<script src="{{ asset('assets/js/v833ccba57c9e4d2798f2e76cebdd09a11778172276447.js') }}" defer></script>
@stack('scripts')
</body>
</html>
