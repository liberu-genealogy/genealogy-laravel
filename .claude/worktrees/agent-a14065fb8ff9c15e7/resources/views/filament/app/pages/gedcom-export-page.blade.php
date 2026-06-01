<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Info card --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Export Your Family Tree as GEDCOM
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mb-4">
                GEDCOM (Genealogical Data Communication) is the standard file format for exchanging genealogical data
                between different genealogy software applications.
            </p>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4 mb-4">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                    What's included in the export:
                </h3>
                <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li>All individuals in your family tree</li>
                    <li>Family relationships (parents, spouses, children)</li>
                    <li>Events (births, deaths, marriages)</li>
                    <li>Names and gender information</li>
                </ul>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-4">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    <strong>Note:</strong> The export runs in the background. Once complete it will appear in the
                    <strong>Exported Files</strong> table below — refresh the page to check.
                    Download links are valid for 30 minutes.
                </p>
            </div>
        </div>

        {{-- Exported files table --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Exported Files
                </h2>
                <button
                    wire:click="$refresh"
                    class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400"
                >
                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                    Refresh
                </button>
            </div>

            @php $files = $this->exportedFiles; @endphp

            @if (count($files) === 0)
                <div class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    No exported files yet. Click <strong>Generate GEDCOM</strong> above to create one.
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Generated</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($files as $file)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-document-text class="w-5 h-5 text-gray-400 flex-shrink-0" />
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100 break-all">{{ $file['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                    {{ $file['size'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                    {{ $file['modified'] }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-3">
                                        <a
                                            href="{{ $file['url'] }}"
                                            class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline"
                                            download
                                        >
                                            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                            Download
                                        </a>
                                        <button
                                            wire:click="deleteFile('{{ $file['name'] }}')"
                                            wire:confirm="Are you sure you want to delete this file?"
                                            class="inline-flex items-center gap-1 text-sm font-medium text-danger-600 dark:text-danger-400 hover:underline"
                                        >
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-filament-panels::page>
