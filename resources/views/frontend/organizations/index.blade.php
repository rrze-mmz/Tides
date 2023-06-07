@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-16 h-auto md:mt-32">
        <div class="flex flex-col place-content-center content-center items-center justify-center">
            <h2 class="text-2xl font-bold">Organizations index</h2>
        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-3 items-stretch gap-4 pt-4">
                @forelse($organizations as $organization)
                    <a href="{{ route('frontend.organizations.show', $organization) }}"
                       class="m-2 rounded-lg border-2 border-solid border-black p-2">
                        <div class="flex place-content-center content-center items-center justify-center self-center">
                            <div>
                                <h3 class="pb-6 font-semibold">
                                    {{ $organization->name }}
                                </h3>
                            </div>
                        </div>
                    </a>
                @empty
                    <li class="my-2 w-full rounded bg-white p-4">
                        {{ __('organization.index.No organizations found') }}
                    </li>
                @endforelse
            </div>
        </ul>
    </main>
@endsection
