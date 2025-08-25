<x-filament::widget class="filament-pedigree-chart-widget">
    <x-filament::card>
        <div class="pedigree-chart-header">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Pedigree Chart</h3>
                <div class="flex gap-2">
                    <select wire:model.live="generations" class="rounded border-gray-300 text-sm">
                        <option value="2">2 Generations</option>
                        <option value="3">3 Generations</option>
                        <option value="4">4 Generations</option>
                        <option value="5">5 Generations</option>
                        <option value="6">6 Generations</option>
                    </select>
                    <button wire:click="toggleDates" class="px-3 py-1 text-sm rounded {{ $showDates ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        Dates
                    </button>
                    <button wire:click="togglePhotos" class="px-3 py-1 text-sm rounded {{ $showPhotos ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        Photos
                    </button>
                </div>
            </div>
        </div>

        <div id="pedigree-chart-container" class="pedigree-chart-container">
            @if($tree)
                <div class="pedigree-tree">
                    {!! $this->renderPedigreeTree($tree) !!}
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No data available to display the pedigree chart.</p>
                    <p class="text-sm mt-2">Please select a person to start building the tree.</p>
                </div>
            @endif
        </div>

        @if($rootPerson)
            <div class="mt-4 text-sm text-gray-600">
                <p><strong>Root Person:</strong> {{ $rootPerson->fullname() }}</p>
                <p><strong>Generations:</strong> {{ $generations }}</p>
            </div>
        @endif
    </x-filament::card>

    @push('styles')
        <style>
            .pedigree-chart-container {
                overflow-x: auto;
                overflow-y: hidden;
                min-height: 400px;
                background: #f8fafc;
                border-radius: 8px;
                padding: 20px;
            }

            .pedigree-tree {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                min-width: max-content;
            }

            .person-box {
                background: white;
                border: 2px solid #e2e8f0;
                border-radius: 8px;
                padding: 12px;
                margin: 4px;
                min-width: 180px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                cursor: pointer;
                position: relative;
            }

            .person-box:hover {
                border-color: #3b82f6;
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
                transform: translateY(-2px);
            }

            .person-box.male {
                border-left: 4px solid #3b82f6;
            }

            .person-box.female {
                border-left: 4px solid #ec4899;
            }

            .person-name {
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 4px;
            }

            .person-dates {
                font-size: 0.875rem;
                color: #6b7280;
                line-height: 1.4;
            }

            .generation-level {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
            }

            .generation-level:not(:first-child) {
                margin-left: 40px;
            }

            .connection-line {
                width: 40px;
                height: 2px;
                background: #d1d5db;
                margin: 0 10px;
            }

            .parents-container {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .level-0 .person-box {
                border-width: 3px;
                border-color: #059669;
                background: #f0fdf4;
            }

            .level-1 .person-box {
                background: #fef3c7;
            }

            .level-2 .person-box {
                background: #fce7f3;
            }

            .level-3 .person-box {
                background: #e0f2fe;
            }

            .expand-btn {
                position: absolute;
                top: -8px;
                right: -8px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: #3b82f6;
                color: white;
                border: none;
                font-size: 12px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .expand-btn:hover {
                background: #2563eb;
            }

            @media (max-width: 768px) {
                .pedigree-chart-container {
                    padding: 10px;
                }
                
                .person-box {
                    min-width: 140px;
                    padding: 8px;
                }
                
                .generation-level:not(:first-child) {
                    margin-left: 20px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('refreshChart', () => {
                    // Chart will be refreshed automatically by Livewire
                    console.log('Pedigree chart refreshed');
                });
            });

            function expandPerson(personId) {
                @this.call('expandPerson', personId);
            }
        </script>
    @endpush
</x-filament::widget>