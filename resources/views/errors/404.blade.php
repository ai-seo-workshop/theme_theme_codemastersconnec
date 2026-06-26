@extends('layout')

@section('title', \App\Models\MaterielTask::page_not_found(app()->getLocale()))
@section('description', \App\Models\MaterielTask::desc_1_404(app()->getLocale()))

@section('content')
    @php
        $locale = app()->getLocale();
        $defaultLocale = config('app.default_language', 'en');
        $homeUrl = $locale === $defaultLocale
            ? route_slash('home')
            : (\Illuminate\Support\Facades\Route::has('home.localized') ? route_slash('home.localized', ['locale' => $locale]) : url('/' . $locale . '/'));
    @endphp
    <div id="content" class="container-wrapper">
        <section class="error-404-wrap">
            <h1>{{ \App\Models\MaterielTask::page_not_found($locale) }}</h1>
            <p>{{ \App\Models\MaterielTask::desc_1_404($locale) }}</p>
            <p>{{ \App\Models\MaterielTask::desc_2_404($locale) }}</p>
            <a class="btn-primary" href="{{ $homeUrl }}">{{ \App\Models\MaterielTask::go_to_homepage($locale) }}</a>

            @if(!empty($categories))
                <div class="error-categories">
                    <p class="widget-title">{{ \App\Models\MaterielTask::popular_destinations($locale) }}</p>
                    <ul>
                        @foreach($categories as $category)
                            <li><a href="{{ $category->url }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>
    </div>
@endsection
