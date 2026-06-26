@if(!empty($crumbs) && count($crumbs) > 1)
    <div class="af-breadcrumbs font-family-1 color-pad">
        <nav class="breadcrumb-trail breadcrumbs" aria-label="Breadcrumb">
            <ul class="trail-items">
                @foreach($crumbs as $index => $crumb)
                    <li class="trail-item {{ $loop->last ? 'trail-end' : '' }}">
                        @if($loop->last)
                            <span>{{ $crumb['title'] }}</span>
                        @else
                            <a href="{{ $crumb['absolute_url'] }}">{{ $crumb['title'] }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
@endif
