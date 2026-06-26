<div class="af-container-row aft-archive-wrapper darknews-customizer clearfix archive-layout-grid two-col-masonry">
    @forelse($blogs as $blog)
        @include('partials.read-single-card', ['blog' => $blog])
    @empty
        <p class="empty-posts">{{ \App\Models\MaterielTask::recent_posts(app()->getLocale()) }}: 0</p>
    @endforelse
</div>
