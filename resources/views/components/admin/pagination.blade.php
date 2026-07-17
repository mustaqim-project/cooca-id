@props(['paginator' => null, 'total' => null, 'perPage' => null])

@if ($paginator && method_exists($paginator, 'hasPages') && $paginator->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="text-secondary" style="font-size: 0.85rem;">
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $perPage = $paginator->perPage();
                $from = ($current - 1) * $perPage + 1;
                $to = min($current * $perPage, $paginator->total());
                $total = $paginator->total();
            @endphp
            Showing <span class="fw-medium text-body">{{ $from }}</span> to
            <span class="fw-medium text-body">{{ $to }}</span> of
            <span class="fw-medium text-body">{{ number_format($total) }}</span> entries
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0 gap-1 flex-wrap">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span
                            class="page-link rounded-circle border-0 bg-light text-muted d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link rounded-circle border-0 bg-white shadow-sm text-secondary hover-lift d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Page Numbers --}}
                @foreach ($paginator->links()->elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span
                                class="page-link border-0 bg-transparent text-muted px-2">{{ $element }}</span>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active">
                                    <span
                                        class="page-link rounded-circle border-0 bg-primary text-white shadow-sm d-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px;">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-circle border-0 bg-white shadow-sm text-secondary hover-lift d-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px;"
                                        href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-circle border-0 bg-white shadow-sm text-secondary hover-lift d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;" href="{{ $paginator->nextPageUrl() }}" rel="next"
                            aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span
                            class="page-link rounded-circle border-0 bg-light text-muted d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@elseif($total !== null)
    <div class="text-secondary text-center py-2" style="font-size: 0.85rem;">
        Showing <span class="fw-medium text-body">{{ number_format($total) }}</span> entries
    </div>
@endif
