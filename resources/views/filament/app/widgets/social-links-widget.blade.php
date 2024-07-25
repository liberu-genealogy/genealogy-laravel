<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium mb-4">Social Links</h2>
        <div class="space-y-4">
            @foreach($links as $name => $link)
                @if(is_array($link))
                    <div>
                        <h3 class="text-md font-medium mb-2">{{ $name }}</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($link as $subName => $subLink)
                                <li>
                                    <a href="{{ $subLink }}" target="_blank" class="text-primary-600 hover:underline">
                                        {{ $subName }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div>
                        <a href="{{ $link }}" target="_blank" class="text-primary-600 hover:underline">
                            {{ $name }}
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </x-filament::card>
</x-filament::widget>