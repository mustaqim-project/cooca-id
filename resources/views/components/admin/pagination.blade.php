@props(['paginator' => null, 'total' => null, 'perPage' => null])

@if ($paginator && method_exists($paginator, 'hasPages') && $paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="portal-pagination">
        <div class="portal-pagination-info">
            Menampilkan
            <span class="font-bold text-primary">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span class="font-bold text-primary">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-bold text-primary">{{ number_format($paginator->total()) }}</span>
            data
        </div>

        <ul class="portal-pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="portal-page-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                    <span class="portal-page-link"><i class="fa-solid fa-chevron-left"></i></span>
                </li>
            @else
                <li class="portal-page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="portal-page-link" aria-label="Sebelumnya">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($paginator->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="portal-page-item disabled" aria-disabled="true">
                        <span class="portal-page-link dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="portal-page-item active" aria-current="page">
                                <span class="portal-page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="portal-page-item">
                                <a href="{{ $url }}" class="portal-page-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="portal-page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="portal-page-link" aria-label="Berikutnya">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="portal-page-item disabled" aria-disabled="true" aria-label="Berikutnya">
                    <span class="portal-page-link"><i class="fa-solid fa-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@elseif($total !== null)
    <div class="portal-pagination simple">
        <div class="portal-pagination-info">
            Total <span class="font-bold text-primary">{{ number_format($total) }}</span> data
        </div>
    </div>
@endif
