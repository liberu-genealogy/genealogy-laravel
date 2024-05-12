@livewire('pedigree-chart')
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
            const container = d3.select('#pedigree-chart-container');
            const width = 800;
            const height = 600;
    
            const svg = container.append('svg')
                .attr('width', width)
                .attr('height', height);
    
            // Render pedigree chart using peopleData
            // ...
        }
    
        @this.on('zoomIn', () => {
            // Implement zoom in logic
            // ...
        });
    
        @this.on('zoomOut', () => {
            // Implement zoom out logic
            // ...
        });
    
        @this.on('pan', direction => {
            // Implement panning logic based on direction
            // ...  
        });
    });
</script>
@endpush

@once
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pedigree-chart.css') }}">
@endpush
@endonce
