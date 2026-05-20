<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Module Name</h4>
            <p class="text-gray-600 dark:text-gray-400">{{ $module['name'] }}</p>
        </div>
        <div>
            <h4 class="font-semibold text-gray-900 dark:text-gray-100">Version</h4>
            <p class="text-gray-600 dark:text-gray-400">{{ $module['version'] }}</p>
        </div>
    </div>

    <div>
        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Description</h4>
        <p class="text-gray-600 dark:text-gray-400">{{ $module['description'] }}</p>
    </div>

    @if(!empty($module['dependencies']))
    <div>
        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Dependencies</h4>
        <div class="flex flex-wrap gap-2 mt-2">
            @foreach($module['dependencies'] as $dependency)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ $dependency }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    <div>
        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Status</h4>
        <div class="flex items-center mt-2">
            @if($module['enabled'])
                <div class="flex items-center text-green-600 dark:text-green-400">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Enabled
                </div>
            @else
                <div class="flex items-center text-red-600 dark:text-red-400">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    Disabled
                </div>
            @endif
        </div>
    </div>

    @if(!empty($module['config']))
    <div>
        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Configuration</h4>
        <div class="mt-2 bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
            <pre class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ json_encode($module['config'], JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
    @endif
</div>