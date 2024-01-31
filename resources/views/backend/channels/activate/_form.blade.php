<div class="flex pt-10">
    <div class="mx-auto w-full">
        <div
            class="w-full px-8 py-6 text-left  bg-gray-200 dark:bg-slate-800"
        >
            <div class="flex items-center justify-between dark:text-white text-2xl "
            >
                                    <span>
                                        Channel activation
                                    </span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800">
            <div class="p-2 bg-green-100 dark:bg-green-800 font-normal my-2 dark:text-white">
                {{{ __('myPortal.channels.introduction') }}}
            </div>
            <div class="flex justify-center content-center  py-2 px-2">
                <form action="{{ route('channels.store') }}"
                      method="POST"
                      class="w-full"
                >
                    @csrf

                    <div class="flex flex-col gap-3">
                        <x-form.input field-name="url_handle"
                                      input-type="text"
                                      :value="'@'.Str::before(auth()->user()->email,'@')"
                                      label="Url handle"
                                      :fullCol="true"
                                      :read-only="true"
                                      :required="true" />
                        <x-form.input field-name="name"
                                      input-type="text"
                                      :value="auth()->user()->getFullNameAttribute()"
                                      label="Name"
                                      :fullCol="true"
                                      :required="true" />
                        <x-form.textarea field-name="description"
                                         :value="''"
                                         label="Description" />
                        <div class="flex py-10">
                            <x-button
                                class="bg-blue-600 hover:bg-blue-700 w-3/12 content-center justify-center
                                     items-center"
                            >
                                {{ __('myPortal.channels.activate button') }}
                            </x-button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
