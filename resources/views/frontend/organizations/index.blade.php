@extends('layouts.frontend')

@section('content')
    <main class="container mx-auto mt-16 h-auto md:mt-32">
        <div class="flex flex-col justify-center content-center items-center place-content-center">
            <h2 class="text-2xl font-bold">Organizations index</h2>
        </div>
        <ul class="flex-row">
            <div class="grid grid-cols-3 gap-4 pt-4 items-stretch">
                @forelse($organizations as $organization)
                    <a href="{{ route('frontend.organizations.show', $organization) }}"
                       class="m-2 p-2 border-black border-solid rounded-lg border-2 ">
                        <div class="flex self-center  justify-center content-center items-center place-content-center">
                            <div>
                                <h3 class="pb-6 font-semibold">
                                    {{ $organization->name }}
                                </h3>
                            </div>
                        </div>
                    </a>
                @empty
                    <li class="w-full p-4 bg-white my-2 rounded">
                        Portal has no faculties yet!
                    </li>
                @endforelse
            </div>
        </ul>
    </main>
@endsection
