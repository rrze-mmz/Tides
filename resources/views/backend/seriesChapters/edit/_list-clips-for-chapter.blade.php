<div class="flex my-10  pb-1  font-medium border-b border-black font-3xl">
    Clips for this chapter
</div>
<form action="">
    @forelse($chapter->clips as $clip)
        <div class="flex-row">
            <div class="pb-6">
                <div class="flex align-content-center align-middle">
                    <x-checkbox name="{{ $clip->id }}"/>
                    <label class="pl-2 inline-block text-gray-800" for="{{ $clip->id }}">
                        {{ $clip->title }}
                    </label>
                </div
            </div>
            @empty
                <p>
                    {{ 'No clips found for chapter '.$chapter->title }}
                </p>
            @endforelse
            <div class="pt-8">
                <x-form.button :link="$link=false" type="submit" text="Remove selected clips from chapter"/>
            </div>
</form>
