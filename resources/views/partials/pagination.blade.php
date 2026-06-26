@if(isset($paginator) && $paginator->hasPages())
    <div class="darknews-pagination">
        <nav class="navigation pagination" aria-label="Posts pagination">
            <ul class="page-numbers">
                @if(!$paginator->onFirstPage())
                    <li>
                        <a class="page-numbers prev" href="{{ $paginator->previousPageUrl() }}">
                            {{ \App\Models\MaterielTask::previous(app()->getLocale()) }}
                        </a>
                    </li>
                @endif

                @for($page = 1; $page <= $paginator->lastPage(); $page++)
                    <li>
                        @if($page === $paginator->currentPage())
                            <span class="page-numbers current">{{ $page }}</span>
                        @else
                            <a class="page-numbers" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                        @endif
                    </li>
                @endfor

                @if($paginator->hasMorePages())
                    <li>
                        <a class="page-numbers next" href="{{ $paginator->nextPageUrl() }}">
                            {{ \App\Models\MaterielTask::next(app()->getLocale()) }}
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
