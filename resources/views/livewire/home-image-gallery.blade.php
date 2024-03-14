<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach($images as $image)
        <img src="{{ $image }}" class="max-w-full h-auto rounded-lg shadow-md" alt="Image Gallery">
    @endforeach
</div>
