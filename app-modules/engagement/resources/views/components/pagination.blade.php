<div>
    @if ($paginator->hasPages())
        <nav
            class="flex flex-col items-center border-t border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-800 sm:px-6"
            role="navigation"
            aria-label="Pagination Navigation"
        >
            <div class="flex flex-1 flex-col-reverse items-center justify-between">
                <div>
                    <p class="mt-2 text-xs leading-5 text-gray-400 dark:text-gray-500">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span
                                    aria-disabled="true"
                                    aria-label="{{ __('pagination.previous') }}"
                                >
                                    <span
                                        class="relative -ml-px inline-flex cursor-default items-center rounded-l-md bg-transparent px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 hover:dark:bg-gray-700"
                                        aria-hidden="true"
                                    >
                                        <x-filament::icon
                                            class="h-5 w-5 text-gray-400 dark:text-gray-100"
                                            icon="heroicon-o-chevron-left"
                                        />
                                    </span>
                                </span>
                            @else
                                <button
                                    class="relative -ml-px inline-flex cursor-pointer items-center rounded-l-md bg-transparent px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 hover:dark:bg-gray-700"
                                    type="button"
                                    aria-label="{{ __('pagination.previous') }}"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="prev"
                                >
                                    <x-filament::icon
                                        class="h-5 w-5 text-gray-400 dark:text-gray-100"
                                        icon="heroicon-o-chevron-left"
                                    />
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @php
                                    $previousPage = $paginator->currentPage() - 1;
                                    $nextPage = $paginator->currentPage() + 1;
                                @endphp

                                @if ($paginator->currentPage() != 1)
                                    {{-- First Page --}}
                                    <x-engagement::page-link
                                        :pageName="$paginator->getPageName()"
                                        :currentPage="$paginator->currentPage()"
                                        :page="1"
                                    />

                                    {{-- Three dots before current page if applicable --}}
                                    @if ($previousPage > 2)
                                        <span aria-disabled="true">
                                            <span
                                                class="relative -ml-px inline-flex cursor-pointer items-center bg-transparent px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 hover:dark:bg-gray-700"
                                            >...</span>
                                        </span>
                                    @endif
                                @endif

                                {{-- Previous Page --}}
                                @if ($previousPage >= 1 && $previousPage != 1)
                                    <x-engagement::page-link
                                        :pageName="$paginator->getPageName()"
                                        :currentPage="$paginator->currentPage()"
                                        :page="$previousPage"
                                    />
                                @endif

                                {{-- Current Page --}}
                                <x-engagement::page-link
                                    :pageName="$paginator->getPageName()"
                                    :currentPage="$paginator->currentPage()"
                                    :page="$paginator->currentPage()"
                                />

                                {{-- Next Page --}}
                                @if ($nextPage <= $paginator->lastPage() && $nextPage != $paginator->lastPage())
                                    <x-engagement::page-link
                                        :pageName="$paginator->getPageName()"
                                        :currentPage="$paginator->currentPage()"
                                        :page="$nextPage"
                                    />
                                @endif

                                @if ($paginator->currentPage() != $paginator->lastPage())
                                    {{-- Three dots after current page if applicable --}}
                                    @if ($paginator->lastPage() - $nextPage > 1)
                                        <span aria-disabled="true">
                                            <span
                                                class="relative -ml-px inline-flex cursor-pointer items-center bg-transparent px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 hover:dark:bg-gray-700"
                                            >...</span>
                                        </span>
                                    @endif

                                    {{-- Last Page --}}
                                    @if ($paginator->lastPage() > 1)
                                        <x-engagement::page-link
                                            :pageName="$paginator->getPageName()"
                                            :currentPage="$paginator->currentPage()"
                                            :page="$paginator->lastPage()"
                                        />
                                    @endif
                                @endif
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button
                                    class="relative -ml-px inline-flex cursor-pointer items-center rounded-r-md bg-transparent px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 hover:dark:bg-gray-700"
                                    type="button"
                                    aria-label="{{ __('pagination.next') }}"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next"
                                >
                                    <x-filament::icon
                                        class="h-5 w-5 text-gray-400 dark:text-gray-100"
                                        icon="heroicon-o-chevron-right"
                                    />
                                </button>
                            @else
                                <span
                                    aria-disabled="true"
                                    aria-label="{{ __('pagination.next') }}"
                                >
                                    <span
                                        class="relative -ml-px inline-flex cursor-default items-center rounded-r-md bg-transparent px-2 py-2 text-sm font-medium leading-5 text-gray-500 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 hover:dark:bg-gray-700"
                                        aria-hidden="true"
                                    >
                                        <x-filament::icon
                                            class="h-5 w-5 text-gray-400 dark:text-gray-100"
                                            icon="heroicon-o-chevron-right"
                                        />
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>