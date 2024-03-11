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

/**
* File: pedigree-chart.blade.php
*
* This file contains the HTML template for rendering the pedigree chart components.
* It also includes JavaScript for initializing and interacting with the pedigree chart using D3.js and Livewire events.
*/
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pedigree-chart.css') }}">
@endpush
@endonce
