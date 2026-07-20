<x-filament-widgets::widget class="filament-fan-chart-widget">
    <x-filament::card>
        <div id="fanChartContainer"></div>
    </x-filament::card>

    @push('scripts')
        <script src="https://d3js.org/d3.v6.min.js"></script>
        <script src="{{ asset('js/fan-chart.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Single ancestor tree rooted at the selected person (d3.hierarchy
                // expects one rooted node, not a flat list).
                const tree = @json($tree);
                if (tree && tree.id) {
                    initializeFanChart(tree);
                } else {
                    console.warn('No person selected to render the fan chart.');
                }
            });
        </script>
    @endpush
</x-filament-widgets::widget>
