<div {{ $attributes }} >
    <input name="{{ $name }}" id="{{ $id }}" value="{{ old($name, $slot) }}" type="hidden">
    <trix-editor input="{{ $id }}" class="{{ $styling }} bg-white h-96"></trix-editor>
</div>
