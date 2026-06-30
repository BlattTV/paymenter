<div class="flex justify-center">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="{{ __('general.pagination') }}" class="flex gap-2 items-center">
            <span>
                @if ($paginator->onFirstPage())
                    <span class="bg-background-secondary text-base px-4 py-2 rounded-xl cursor-not-allowed">{{ __('general.previous') }}</span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="bg-background-secondary text-base px-4 py-2 rounded-xl">{{ __('general.previous') }}</button>
                @endif
            </span>

            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if (
                            $page == $paginator->currentPage() ||
                                $page <= 2 ||
                                $page > $paginator->lastPage() - 2 ||
                                abs($paginator->currentPage() - $page) <= 1)
                            <span>
                                <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                                    class="{{ $page === $paginator->currentPage() ? 'bg-background-secondary/30 text-base' : 'bg-background-secondary text-base' }} px-4 py-2 rounded-xl cursor-pointer">{{ $page }}</button>
                            </span>
                        @elseif($page == 3 || $page == $paginator->lastPage() - 3)
                            <span class="bg-background-secondary text-base px-4 py-2 rounded-xl">
                                <span>...</span>
                            </span>
                        @endif
                    @endforeach
                @else
                    <span class="bg-background-secondary text-base px-4 py-2 rounded-xl">
                        <span>...</span>
                    </span>
                @endif
            @endforeach


            <span>
                @if ($paginator->onLastPage())
                    <span class="bg-background-secondary text-base px-4 py-2 rounded-xl cursor-not-allowed">{{ __('general.next') }}</span>
                @else
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                        class="bg-background-secondary text-base px-4 py-2 rounded-xl">{{ __('general.next') }}</button>
                @endif
            </span>
        </nav>
    @endif
</div>
