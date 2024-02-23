@can('administrate-admin-portal-pages')
    <div class="flex flex-col font-normal py-4">
        <h4 class="mt-4 mb-4 text-green-700  dark:text-green-400">
            <span class="text-xl font-bold">Opencast access policy</span>
        </h4>
        <div class="flex w-1/2">
            <table class="table-auto border-b border-gray-200 dark:border-white">
                <thead class="text-left">
                <tr class="uppercase dark:text-white">
                    <th class="px-4">
                        Name
                    </th>
                    <th class="pr-4">
                        Read
                    </th>
                    <th class="pr-4">
                        Write
                    </th>
                    <th class="pr-4">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-white">
                @forelse(collect($opencastSeriesInfo['metadata']['acl'])
                        ->groupBy('role')
                        ->except(['ROLE_USER_ADMIN','ROLE_ADMIN','ROLE_STUDIO','ROLE_USER_STUDIO']) as $key=>$role)
                    <tr class="dark:bg-gray-700">
                        <td class="px-4 dark:text-white">
                            @php
                                $user = findUserByOpencastRole($key);
                            @endphp
                            {{-- Opencast can return deleted
                            users in this case the user doesn't exist as assistant anymore. So skip current iteration--}}
                            @if(is_string($user))
                                @break
                            @endif
                            {{ $user->getFullNameAttribute() }}
                        </td>
                        <td class="pr-4">
                            @if($role[0]['allow'] && $role[0]['action'] ==='read')
                                <x-heroicon-o-check-circle class="h-6 w-6 text-green-700" />
                            @else
                                <x-heroicon-o-x-circle class="h-6 w-6 text-red-700" />
                            @endif
                        </td>
                        <td class="pr-4">
                            @if($role[1]['allow'] && $role[1]['action'] ==='write')
                                <x-heroicon-o-check-circle class="h-6 w-6 text-green-700" />
                            @else
                                <x-heroicon-o-x-circle class="h-6 w-6 text-red-700" />
                            @endif
                        </td>
                        <td class="pr-4">
                            <div>
                                <form action="{{route('series.opencast.updateSeriesAcl', $series)}}"
                                      method="POST"
                                      class="flex flex-1 items-center"
                                >
                                    @csrf
                                    <input hidden type="text" name="action" value="removeUser">
                                    <input hidden type="text" name="username" value="{{$user->username}}">
                                    @error('username')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="my-4">
                                        <x-button>
                                            <x-heroicon-o-x-circle class="h-6 w-6 text-red-700" />
                                        </x-button>
                                    </div>
                                </form>
                            </div>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td class="font-bold italic dark:bg-slate-800 dark:text-white py-4" colspan="4">
                            Series in Opencast has no Tides editors
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>


        <h4 class="mt-4 mb-4 text-green-700">
            <span class="text-xl font-bold">Available assistants</span>
        </h4>
        <form action="{{route('series.opencast.updateSeriesAcl', $series)}}"
              method="POST"
              class="flex flex-1 items-center"
        >
            @csrf
            <input hidden readonly type="text" name="opencastSeriesID" value="{{$series->opencast_series_id}}">
            <label class="font-normal mr-4 dark:text-white" for="username">Add assistant to opencast series:</label>
            <select name="username"
                    id="username"
                    class="mr-4 rounded-md text-md focus:border-blue-500 focus:bg-white focus:outline-none">
                @foreach($availableAssistants as $user)
                    <option value="{{$user->username}}">{{ $user->getFullNameAttribute() }}</option>
                @endforeach
            </select>
            <input hidden type="text" name="action" value="addUser">
            @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <x-button>
                <x-heroicon-o-plus-circle class="h-6 w-6 text-green-700" />
            </x-button>
        </form>

    </div>
@endcan
