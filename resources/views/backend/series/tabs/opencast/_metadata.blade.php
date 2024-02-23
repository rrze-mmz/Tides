@php use App\Models\Semester; @endphp
<div class="flex flex-col font-normal">
    <h4 class="mb-4 text-green-700 dark:text-green-400">
        <span class="text-xl font-bold">Opencast Metadata</span> <span
            class="text-sm italic">created at {{ zuluToCEST($opencastSeriesInfo['metadata']['created']) }}</span>
    </h4>
    <div class="dark:text-white">
        <span class="font-bold">
            Title:
        </span>
        <span class="italic">
            {{ $series->title }}
        </span>
    </div>
    <div class="dark:text-white">
            <span class="font-bold">
                Subject
            </span>
        <span class="italic">
            @if($series->clips()->count() > 0)
                {{ $series->latestClip->semester->name }}
            @else
                {{ Semester::current()->first()->name }}
            @endif
        </span>
    </div>
    <div class="dark:text-white">
        <span class="font-bold">
            Presenter:
        </span>
        <span class="italic">
            {{ $series->presenters->first()?->getFullNameAttribute() }}
        </span>
    </div>
    <div class="dark:text-white">
        <span class="font-bold">
            Contributor:
        </span>
        <span class="italic">
            {{ $series->organization->name }}
        </span>
    </div>
</div>
