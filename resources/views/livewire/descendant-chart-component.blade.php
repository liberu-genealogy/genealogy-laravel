<div class="descendant-chart-container">
    <div class="flex items-center gap-3 mb-4">
        <label class="text-sm text-gray-700">Root person:</label>
        <select class="fi-input block rounded-md border-gray-300 text-sm"
                wire:change="setRootPerson($event.target.value)">
            @foreach($this->peopleList as $id => $label)
                <option value="{{ $id }}" @selected($rootPersonId === (int) $id)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="chart-header mb-4">
        <h3 class="text-xl font-semibold text-gray-800">Descendant Chart</h3>
        <div class="chart-controls flex gap-2 mt-2">
            <button wire:click="setGenerations(3)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 3 ? 'bg-blue-700' : '' }}">3 Gen</button>
            <button wire:click="setGenerations(4)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 4 ? 'bg-blue-700' : '' }}">4 Gen</button>
            <button wire:click="setGenerations(5)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 5 ? 'bg-blue-700' : '' }}">5 Gen</button>
        </div>
    </div>

    <div id="descendant-chart-display" class="chart-display bg-white border rounded-lg p-4" style="min-height: 500px;">
        @if(!empty($descendantsData))
            <div id="descendantChartSvg" class="w-full h-96"></div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🌳</div>
                <h4 class="text-lg font-medium text-gray-600 mb-2">No Descendant Data Available</h4>
                <p class="text-gray-500">Add children to your family tree to see the descendant chart.</p>
            </div>
        @endif
    </div>
    <style>
    .descendant-chart-container {
        width: 100%;
    }

    #descendantChartSvg {
        cursor: grab;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    #descendantChartSvg:active {
        cursor: grabbing;
    }

    .descendant-node {
        cursor: pointer;
    }

    .descendant-node circle {
        fill: #f8f9fa;
        stroke: #007bff;
        stroke-width: 2;
    }

    .descendant-node.male circle {
        fill: #e3f2fd;
        stroke: #007bff;
    }

    .descendant-node.female circle {
        fill: #fce4ec;
        stroke: #e91e63;
    }

    .descendant-node text {
        font-family: Arial, sans-serif;
        font-size: 12px;
        fill: #333;
    }

    .descendant-link {
        fill: none;
        stroke: #ccc;
        stroke-width: 2;
    }

    @media (max-width: 768px) {
        .descendant-node text {
            font-size: 10px;
        }
    }
    </style>

    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>
    document.addEventListener('livewire:init', () => {
        initializeDescendantChart();

        Livewire.on('refreshDescendantChart', () => {
            initializeDescendantChart();
        });
    });

    function initializeDescendantChart() {
        const descendantData = @json($descendantsData ?? []);

        if (descendantData && Object.keys(descendantData).length > 0) {
            renderDescendantChart(descendantData);
        } else {
            console.warn('No data available to render the descendant chart.');
        }
    }

    function renderDescendantChart(data) {
        // Clear existing chart
        d3.select("#descendantChartSvg").selectAll("*").remove();

        const container = d3.select("#descendantChartSvg");
        const containerNode = container.node();
        const width = containerNode.clientWidth || 800;
        const height = containerNode.clientHeight || 600;

        const svg = container
            .append("svg")
            .attr("width", width)
            .attr("height", height);

        const g = svg.append("g")
            .attr("transform", "translate(40,40)");

        // Create tree layout
        const tree = d3.tree()
            .size([height - 80, width - 80]);

        // Create hierarchy
        const root = d3.hierarchy(data);
        tree(root);

        // Add links
        g.selectAll(".descendant-link")
            .data(root.links())
            .enter().append("path")
            .attr("class", "descendant-link")
            .attr("d", d3.linkHorizontal()
                .x(d => d.y)
                .y(d => d.x));

        // Add nodes
        const node = g.selectAll(".descendant-node")
            .data(root.descendants())
            .enter().append("g")
            .attr("class", d => `descendant-node ${d.data.sex || 'unknown'}`)
            .attr("transform", d => `translate(${d.y},${d.x})`)
            .on("click", function(event, d) {
                if (d.data.id) {
                    @this.call('setRootPerson', d.data.id);
                }
            });

        // Add circles
        node.append("circle")
            .attr("r", 20);

        // Add text
        node.append("text")
            .attr("dy", "0.35em")
            .attr("x", d => d.children ? -25 : 25)
            .style("text-anchor", d => d.children ? "end" : "start")
            .text(d => {
                const name = d.data.givn || d.data.name || '';
                return name.length > 12 ? name.substring(0, 12) + '...' : name;
            });

        // Add birth/death years
        node.append("text")
            .attr("dy", "1.5em")
            .attr("x", d => d.children ? -25 : 25)
            .style("text-anchor", d => d.children ? "end" : "start")
            .style("font-size", "10px")
            .style("fill", "#666")
            .text(d => {
                const birth = d.data.birth_date ? new Date(d.data.birth_date).getFullYear() : '';
                const death = d.data.death_date ? new Date(d.data.death_date).getFullYear() : '';
                if (birth && death) return `${birth}-${death}`;
                if (birth) return `b.${birth}`;
                return '';
            });
    }

    function setGenerations(generations) {
        @this.call('setGenerations', generations);
    }
    </script>
</div>
