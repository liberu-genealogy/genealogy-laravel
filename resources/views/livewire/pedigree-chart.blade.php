<div id="pedigree-chart-container">
    <!-- Chart will be rendered inside this div -->
</div>

@push('scripts')
<script src="{{ asset('js/d3.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('initializeChart', event => {
            const peopleData = JSON.parse(event.detail.people);
            initializePedigreeChart(peopleData);
        });

        function initializePedigreeChart(peopleData) {
            // D3.js chart initialization and rendering logic goes here
            // Use `peopleData` to dynamically generate the chart
        }

        @this.on('zoomIn', () => {
            // Zoom in logic
        });

        @this.on('zoomOut', () => {
            // Zoom out logic
        });

        @this.on('pan', direction => {
            // Pan logic based on `direction`
        });
    });
</script>
@endpush

@once
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pedigree-chart.css') }}">
@endpush
@endonce
