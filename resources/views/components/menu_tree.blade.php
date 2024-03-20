@php
use App\Models\Category;
use Illuminate\Support\Facades\File;
$categories = Category::all();
$resourcePaths = File::directories(app_path('Filament/Resources'));
$resources = collect($resourcePaths)->map(function ($path) {
    return basename($path);
});
@endphp

<div class="menu-tree">
    @foreach ($categories as $category)
        <div class="category">
            <h3>{{ $category->name }}</h3>
            <ul>
                @foreach ($resources as $resource)
                    @php
                    $routeName = 'filament.resources.'. strtolower($resource) .'.index';
                    @endphp
                    <li><a href="{{ route($routeName) }}">{{ $resource }}</a></li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
