<nav class="flex flex-wrap justify-center sm:justify-end text-dark dark:text-white space-x-2 sm:space-x-4
            text-sm sm:text-base"
>
    <span class="no-underline hover:underline">
        <a href="{{ route('frontend.contact') }}">
            {{ __('common.menu.contact') }}
        </a>
    </span>
    <span class="no-underline hover:underline">
        <a href="{{ route('frontend.series.index') }}">
            {{ __('common.menu.error report') }}
        </a>
    </span>
    <span class="no-underline hover:underline">
        <a href="{{ route('frontend.privacy') }}">
            {{ __('common.menu.imprint') }}
        </a>
    </span>
    <span class="no-underline hover:underline">
        <a href="{{ route('frontend.privacy') }}">
            {{ __('common.menu.privacy') }}
        </a>
    </span>
    <span class="no-underline hover:underline">
        <a href="{{ route('frontend.accessibility') }}">
            {{ __('common.menu.accessibility') }}
        </a>
    </span>
</nav>
