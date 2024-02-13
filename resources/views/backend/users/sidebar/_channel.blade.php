@use(App\Enums\Role)
@if($user->hasRole(Role::MODERATOR) && $user->channels->count() == 0)
    <div
        class="mx-4 h-full w-full rounded-md border bg-white px-4 py-4 font-normal dark:bg-gray-800 dark:border-blue-800">
        <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 dark:border-blue-800 py-4 pl-4 text-xl
    dark:text-white"
        >
            User Channel
        </h2>
        <form action="{{route('channels.activate')}}"
              method="POST">
            @csrf
            <input type="text"
                   name="username"
                   value="{{ $user->username}}"
                   hidden
            />
            <x-button type="submit"
                      class="bg-fuchsia-600 hover:bg-fuchsia:700 w-full text-center content-center">
                Enable user channel
            </x-button>
        </form>
    </div>
@elseif($user->channels()->count() > 0)
    <div
        class="mx-4 h-full w-full rounded-md border bg-white px-4 py-4 font-normal dark:bg-gray-800 dark:border-blue-800">
        <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 dark:border-blue-800 py-4 pl-4 text-xl
    dark:text-white"
        >
            User Channel
        </h2>
        <div class="flex-row">
            @include('partials.channels._card',['channel' => $user->channels()->first()])
        </div>
    </div>
@endif
