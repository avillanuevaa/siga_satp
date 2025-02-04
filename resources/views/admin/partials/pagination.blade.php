@if ($paginator->hasPages())
<ul class="pagination pagination-sm m-0 float-right">
	{{-- First Page Link --}}
	<li class="page-item @if ($paginator->onFirstPage()) disabled @endif">
			<a class="page-link" href="{{ $paginator->url(1) }}" @if ($paginator->onFirstPage()) tabindex="-1" @endif>Primera</a>
	</li>
	{{-- Previous Page Link --}}
	<li class="page-item @if ($paginator->onFirstPage()) disabled @endif">
		<a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
	</li>

	{{-- Pagination Elements --}}
	@foreach ($elements as $element)
		{{-- "Three Dots" Separator --}}
		@if (is_string($element))
			<li class="page-item disabled">
				<a class="page-link" >{{ $element }}</a>
			</li>
		@endif

		{{-- Array Of Links --}}
		@if (is_array($element))
			@foreach ($element as $page => $url)
					<li class="page-item @if ($page == $paginator->currentPage()) active @endif">
						<a class="page-link" href="{{ $url }}">{{ $page }}</a>
					</li>
			@endforeach
		@endif
	@endforeach

	{{-- Next Page Link --}}
	<li class="page-item @unless ($paginator->hasMorePages()) disabled @endunless">
		<a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
	</li>
	{{-- Last Page Link --}}
	<li class="page-item @if ($paginator->currentPage() == $paginator->lastPage()) disabled @endif">
			<a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" @if ($paginator->currentPage() == $paginator->lastPage()) tabindex="-1" @endif>Última</a>
	</li>
</ul>
@endif