<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <header class="items-center pb-2 mb-6 font-semibold text-center border-b">
        LMS Test Link
    </header>
    <div class="flex content-center justify-start mb-4">
        <x-form.button :link="generateLMSToken($clip, dechex(time()), true)"
                       type='back'
                       text="LMS Test Link"
        />
    </div>
</div>
