@props([
    // Laravel LengthAwarePaginator instance
    'paginator',
    // Show "Mostrando X a Y..." below the buttons
    'summary' => false,
    // Alignment: start | center | end
    'align' => 'center',
    // Size: null | sm | lg
    'size' => null,
])

@if ($paginator && $paginator->hasPages())
    @php
        $current = (int) $paginator->currentPage();
        $last = (int) $paginator->lastPage();
        $window = 1; // pages on each side of current

        // Build compact page list: first, window around current, last with ellipses where needed
        $pages = [];
        $pages[] = 1;
        $start = max(2, $current - $window);
        $end = min($last - 1, $current + $window);
        if ($start > 2) { $pages[] = 'gap'; }
        for ($i = $start; $i <= $end; $i++) { if ($last > 1) $pages[] = $i; }
        if ($end < $last - 1) { $pages[] = 'gap'; }
        if ($last > 1) { $pages[] = $last; }

        $alignClass = $align === 'end' ? 'justify-content-end' : ($align === 'start' ? '' : 'justify-content-center');
        $sizeClass = $size ? 'pagination-' . $size : '';
    @endphp

    <div class="d-flex flex-column align-items-center">
        <nav role="navigation" aria-label="Paginação">
            <ul class="pagination {{ $alignClass }} mb-0 {{ $sizeClass }}">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="Anterior">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Anterior">&lsaquo;</a>
                    </li>
                @endif

                {{-- Numbers --}}
                @foreach ($pages as $page)
                    @if ($page === 'gap')
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">&hellip;</span></li>
                    @elseif ($page == $current)
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima">&rsaquo;</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="Próxima">
                        <span class="page-link" aria-hidden="true">&rsaquo;</span>
                    </li>
                @endif
            </ul>
        </nav>

        @if ($summary)
            <div class="text-muted small mt-2">
                @if ($paginator->total() > 0)
                    Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
                @else
                    Nenhum resultado
                @endif
            </div>
        @endif
    </div>
@endif
