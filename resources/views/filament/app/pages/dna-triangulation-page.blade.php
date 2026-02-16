<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            DNA Triangulation Analysis
        </x-slot>

        <x-slot name="description">
            Match your DNA kit against other kits to find genetic connections and shared segments.
        </x-slot>

        <form wire:submit="runTriangulation">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament::button type="submit">
                    Run Triangulation
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    @if($this->hasResults())
        <x-filament::section class="mt-6">
            <x-slot name="heading">
                Triangulation Results
            </x-slot>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-filament::card>
                        <div class="text-sm text-gray-500">Total Compared</div>
                        <div class="text-2xl font-bold">{{ $results['total_compared'] ?? 0 }}</div>
                    </x-filament::card>

                    <x-filament::card>
                        <div class="text-sm text-gray-500">Significant Matches</div>
                        <div class="text-2xl font-bold text-success-600">{{ $results['significant_matches'] ?? 0 }}</div>
                    </x-filament::card>

                    <x-filament::card>
                        <div class="text-sm text-gray-500">Base Kit</div>
                        <div class="text-lg font-semibold">{{ $this->getBaseKit()['name'] ?? 'Unknown' }}</div>
                    </x-filament::card>
                </div>

                @if(count($this->getMatches()) > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4">Match Details</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kit Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shared cM</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Largest Segment</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Relationship</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($this->getMatches() as $match)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $match['kit_name'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($match['total_cms'], 2) }} cM
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($match['largest_cm'], 2) }} cM
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $match['confidence_level'] >= 80 ? 'bg-green-100 text-green-800' : 
                                                       ($match['confidence_level'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $match['predicted_relationship'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $match['confidence_level'] }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($match['match_quality_score'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No matches found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            No significant matches were found with the current threshold.
                        </p>
                    </div>
                @endif
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
