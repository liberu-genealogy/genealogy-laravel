<div class="fan-chart-container">
    <div class="chart-header mb-4">
        <h3 class="text-xl font-semibold text-gray-800">Fan Chart</h3>
        <div class="chart-controls flex gap-2 mt-2">
            <button wire:click="setGenerations(3)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 3 ? 'bg-blue-700' : '' }}">3 Gen</button>
            <button wire:click="setGenerations(4)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 4 ? 'bg-blue-700' : '' }}">4 Gen</button>
            <button wire:click="setGenerations(5)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 5 ? 'bg-blue-700' : '' }}">5 Gen</button>
            <button wire:click="toggleNames" class="px-3 py-1 bg-gray-500 text-white rounded {{ $showNames ? 'bg-gray-700' : '' }}">{{ $showNames ? 'Hide' : 'Show' }} Names</button>
            <button wire:click="toggleDates" class="px-3 py-1 bg-gray-500 text-white rounded {{ $showDates ? 'bg-gray-700' : '' }}">{{ $showDates ? 'Hide' : 'Show' }} Dates</button>
        </div>
    </div>

    <div id="fan-chart-display" class="chart-display bg-white border rounded-lg p-4" style="min-height: 500px;">
        @if(!empty($fanData))
            <div id="fanChartSvg" class="w-full h-96"></div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🌟</div>
                <h4 class="text-lg font-medium text-gray-600 mb-2">No Family Data Available</h4>
                <p class="text-gray-500">Add people to your family tree to see the fan chart.</p>
            </div>
        @endif
    </div>
</div>

<style>
.fan-chart-container {
    width: 100%;
}

#fanChartSvg {
    cursor: grab;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

#fanChartSvg:active {
    cursor: grabbing;
}

.fan-segment {
    stroke: #fff;
    stroke-width: 1;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.fan-segment:hover {
    opacity: 0.8;
}

.fan-text {
    font-family: Arial, sans-serif;
    font-size: 10px;
    fill: #333;
    pointer-events: none;
}

.fan-center {
    fill: #f8f9fa;
    stroke: #dee2e6;
    stroke-width: 2;
}

@media (max-width: 768px) {
    .fan-text {
        font-size: 8px;
    }
}
</style>

<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
document.addEventListener('livewire:init', () => {
    initializeFanChart();

    Livewire.on('refreshFanChart', () => {
        initializeFanChart();
    });
});

function initializeFanChart() {
    const fanData = @json($fanData ?? []);
    const config = {
        showNames: @json($showNames),
        showDates: @json($showDates),
        generations: @json($generations),
        colorScheme: @json($colorScheme)
    };

    if (fanData && Object.keys(fanData).length > 0) {
        renderFanChart(fanData, config);
    } else {
        console.warn('No data available to render the fan chart.');
    }
}

function renderFanChart(data, config) {
    // Clear existing chart
    d3.select("#fanChartSvg").selectAll("*").remove();

    const container = d3.select("#fanChartSvg");
    const containerNode = container.node();
    const width = containerNode.clientWidth || 600;
    const height = containerNode.clientHeight || 400;
    const radius = Math.min(width, height) / 2 - 20;

    const svg = container
        .append("svg")
        .attr("width", width)
        .attr("height", height);

    const g = svg.append("g")
        .attr("transform", `translate(${width/2},${height/2})`);

    // Create partition layout
    const partition = d3.partition()
        .size([2 * Math.PI, radius]);

    // Create hierarchy
    const root = d3.hierarchy(data)
        .sum(d => d.children ? 0 : 1)
        .sort((a, b) => b.value - a.value);

    partition(root);

    // Color scale
    const color = d3.scaleOrdinal(d3.schemeCategory10);

    // Create arc generator
    const arc = d3.arc()
        .startAngle(d => d.x0)
        .endAngle(d => d.x1)
        .innerRadius(d => d.y0)
        .outerRadius(d => d.y1);

    // Draw segments
    g.selectAll("path")
        .data(root.descendants().slice(1))
        .enter().append("path")
        .attr("class", "fan-segment")
        .attr("d", arc)
        .style("fill", d => color(d.data.sex || 'unknown'))
        .on("click", function(event, d) {
            if (d.data.id) {
                @this.call('setRootPerson', d.data.id);
            }
        });

    // Add text labels if enabled
    if (config.showNames) {
        g.selectAll("text")
            .data(root.descendants().slice(1))
            .enter().append("text")
            .attr("class", "fan-text")
            .attr("transform", function(d) {
                const x = (d.x0 + d.x1) / 2 * 180 / Math.PI;
                const y = (d.y0 + d.y1) / 2;
                return `rotate(${x - 90}) translate(${y},0) rotate(${x < 180 ? 0 : 180})`;
            })
            .attr("dy", "0.35em")
            .style("text-anchor", "middle")
            .text(d => {
                const name = d.data.givn || d.data.name || '';
                return name.length > 10 ? name.substring(0, 10) + '...' : name;
            });
    }

    // Add center circle
    g.append("circle")
        .attr("class", "fan-center")
        .attr("r", 30)
        .on("click", function() {
            @this.call('setRootPerson', @json($rootPersonId));
        });

    // Add center text
    g.append("text")
        .attr("class", "fan-text")
        .attr("text-anchor", "middle")
        .attr("dy", "0.35em")
        .style("font-size", "12px")
        .style("font-weight", "bold")
        .text("Root");
}
</script>
