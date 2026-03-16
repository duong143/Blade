@if ($paginator->hasPages())
<nav class="combo-pagination-wrap" aria-label="Pagination">
    <ul class="combo-pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li class="combo-page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="combo-page-link combo-page-arrow" aria-hidden="true">
                <i class="fa-solid fa-chevron-left"></i>
            </span>
        </li>
        @else
        <li class="combo-page-item">
            <a class="combo-page-link combo-page-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li class="combo-page-item disabled" aria-disabled="true">
            <span class="combo-page-link combo-page-dots">{{ $element }}</span>
        </li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="combo-page-item active" aria-current="page">
            <span class="combo-page-link">{{ $page }}</span>
        </li>
        @else
        <li class="combo-page-item">
            <a class="combo-page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li class="combo-page-item">
            <a class="combo-page-link combo-page-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </li>
        @else
        <li class="combo-page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="combo-page-link combo-page-arrow" aria-hidden="true">
                <i class="fa-solid fa-chevron-right"></i>
            </span>
        </li>
        @endif
    </ul>
</nav>
@endif