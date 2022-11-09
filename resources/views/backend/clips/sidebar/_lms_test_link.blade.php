<div class="w-full py-4 px-4 mx-4 h-full bg-white rounded border">
    <h2 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-600 pl-4 ">
        LMS Test Link
    </h2>
    <div class="flex content-center justify-start mb-4">
        <x-form.button :link="getAccessToken($clip, dechex(time()), 'studon',true)"
                       type='back'
                       text="LMS Test Link"
        />
    </div>
</div>
