<div class="mx-4 h-full w-full rounded border bg-white px-4 py-4 font-normal dark:bg-gray-800  dark:border-blue-800">
    <h2 class="mb-3 -ml-5 border-l-4 border-blue-600 py-4 pl-4 text-xl dark:text-white">
        LMS Test Link
    </h2>
    <div class="mb-4 flex content-center justify-start">
        <x-form.button :link="getAccessToken($clip, dechex(time()), 'studon',true)"
                       type='back'
                       text="LMS Test Link"
        />
    </div>
</div>
