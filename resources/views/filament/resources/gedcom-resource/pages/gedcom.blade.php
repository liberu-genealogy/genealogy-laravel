<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Upload a GEDCOM or GrampsXML File
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Import your family tree data by uploading a GEDCOM (<code>.ged</code>) or GrampsXML
                (<code>.gramps</code>, <code>.xml</code>) file. The file will be processed in the background
                and you will be redirected to the Import Logs page to monitor progress.
            </p>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4 mb-6">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                    Supported file formats:
                </h3>
                <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li><strong>.ged</strong> – Standard GEDCOM format (most genealogy software)</li>
                    <li><strong>.gramps</strong> – Gramps native XML format</li>
                    <li><strong>.xml</strong> – GrampsXML format</li>
                </ul>
            </div>

            <x-filament-panels::form wire:submit="create">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>
        </div>

        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-4">
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                <strong>Note:</strong> After submitting, your file will be queued for processing
                and you will be redirected to the <strong>Import Logs</strong> page where you can
                monitor the import progress in real time. Large files may take several minutes to process.
            </p>
        </div>
    </div>
</x-filament-panels::page>
