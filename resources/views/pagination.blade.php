<style type="text/css">
    /* Custom pagination styles */
.pagination {
    display: flex;
    list-style: none;
    padding-left: 0;
    margin: 20px 0;
}

.pagination .page-item {
    margin: 0 5px;
}

.pagination .page-link {
    color: #007bff;
    border: 1px solid #007bff;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    color: #fff;
}

.pagination .page-item:hover .page-link {
    background-color: #0056b3;
    color: #fff;
}

.pagination .page-item.disabled .page-link {
    background-color: #f0f0f0;
    color: #ccc;
}

</style>
@if ($paginator->hasPages())
    <ul class="pagination justify-content-around">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link">Previous</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
@endif
