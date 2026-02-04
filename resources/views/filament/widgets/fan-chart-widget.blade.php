<x-filament::widget class="filament-fan-chart-widget">
    <x-filament::card>
        <div class="fan-chart-header">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Fan Chart</h3>
                <div class="flex gap-2 flex-wrap">
                    <select wire:model.live="generations" class="rounded border-gray-300 text-sm">
                        <option value="2">2 Generations</option>
                        <option value="3">3 Generations</option>
                        <option value="4">4 Generations</option>
                        <option value="5">5 Generations</option>
                        <option value="6">6 Generations</option>
                        <option value="7">7 Generations</option>
                        <option value="8">8 Generations</option>
                    </select>
                    <button wire:click="toggleNames" class="px-3 py-1 text-sm rounded {{ $showNames ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        Names
                    </button>
                    <button wire:click="toggleDates" class="px-3 py-1 text-sm rounded {{ $showDates ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        Dates
                    </button>
                    <select wire:model.live="colorScheme" class="rounded border-gray-300 text-sm">
                        <option value="generation">By Generation</option>
                        <option value="gender">By Gender</option>
                        <option value="branch">By Branch</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="fan-chart-container" class="fan-chart-container">
            @if($fanData)
                <div class="fan-chart-wrapper">
                    <div id="fanChartSvg"></div>
                    <div class="fan-chart-controls">
                        <button onclick="zoomIn()" class="control-btn" title="Zoom In">+</button>
                        <button onclick="zoomOut()" class="control-btn" title="Zoom Out">-</button>
                        <button onclick="resetZoom()" class="control-btn" title="Reset">⌂</button>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No data available to display the fan chart.</p>
                    <p class="text-sm mt-2">Please select a person to start building the chart.</p>
                </div>
            @endif
        </div>

        @if($rootPerson)
            <div class="mt-4 text-sm text-gray-600">
                <p><strong>Root Person:</strong> {{ $rootPerson->fullname() }}</p>
                <p><strong>Generations:</strong> {{ $generations }}</p>
                <p><strong>Color Scheme:</strong> {{ ucfirst($colorScheme) }}</p>
            </div>
        @endif
    </x-filament::card>

    @push('styles')
        <style>
            /* Thumbnail and tooltip styles */
            .fan-tooltip img {
                width: 48px;
                height: 48px;
                object-fit: cover;
                border-radius: 6px;
                margin-right: 8px;
                vertical-align: middle;
            }

            .person-thumb img {
                width: 40px;
                height: 40px;
                object-fit: cover;
                border-radius: 6px;
                display: inline-block;
                margin-right: 8px;
            }

            .fan-tooltip .tooltip-content {
                display: flex;
                align-items: center;
            }

            .fan-chart-container {
                position: relative;
                min-height: 600px;
                background: #f8fafc;
                border-radius: 8px;
                overflow: hidden;
            }

            .fan-chart-wrapper {
                position: relative;
                width: 100%;
                height: 600px;
            }

            #fanChartSvg {
                width: 100%;
                height: 100%;
                cursor: grab;
            }

            #fanChartSvg:active {
                cursor: grabbing;
            }

            .fan-chart-controls {
                position: absolute;
                top: 10px;
                right: 10px;
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .control-btn {
                width: 35px;
                height: 35px;
                border: none;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                transition: all 0.2s ease;
            }

            .control-btn:hover {
                background: white;
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
                transform: scale(1.1);
            }

            .fan-segment {
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .fan-segment:hover {
                stroke: #3b82f6;
                stroke-width: 2;
                filter: brightness(1.1);
            }

            .fan-text {
                pointer-events: none;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-size: 11px;
                fill: #1f2937;
            }

            .fan-text.name {
                font-weight: 600;
            }

            .fan-text.dates {
                font-size: 9px;
                fill: #6b7280;
            }

            .generation-0 { fill: #10b981; }
            .generation-1 { fill: #3b82f6; }
            .generation-2 { fill: #8b5cf6; }
            .generation-3 { fill: #f59e0b; }
            .generation-4 { fill: #ef4444; }
            .generation-5 { fill: #ec4899; }
            .generation-6 { fill: #06b6d4; }
            .generation-7 { fill: #84cc16; }

            .gender-male { fill: #3b82f6; }
            .gender-female { fill: #ec4899; }
            .gender-unknown { fill: #6b7280; }

            .branch-paternal { fill: #3b82f6; }
            .branch-maternal { fill: #ec4899; }
            .branch-root { fill: #10b981; }

            @media (max-width: 768px) {
                .fan-chart-container {
                    min-height: 400px;
                }

                .fan-chart-wrapper {
                    height: 400px;
                }

                .fan-chart-header .flex {
                    flex-direction: column;
                    gap: 2;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://d3js.org/d3.v7.min.js"></script>
        <script>
            let fanChart = null;
            let currentData = null;
            let currentConfig = null;

            document.addEventListener('livewire:init', () => {
                initializeFanChart();

                Livewire.on('refreshFanChart', () => {
                    setTimeout(() => {
                        initializeFanChart();
                    }, 100);
                });
            });

            function initializeFanChart() {
                const fanData = @json($fanData);
                const config = {
                    showNames: @json($showNames),
                    showDates: @json($showDates),
                    colorScheme: @json($colorScheme),
                    generations: @json($generations)
                };

                if (!fanData || Object.keys(fanData).length === 0) {
                    console.warn('No data available to render the fan chart.');
                    return;
                }

                currentData = fanData;
                currentConfig = config;
                renderFanChart(fanData, config);
            }

            function renderFanChart(data, config) {
                // Clear existing chart
                d3.select("#fanChartSvg").selectAll("*").remove();

                const container = d3.select("#fanChartSvg");
                const containerNode = container.node();
                const rect = containerNode.getBoundingClientRect();
                const width = rect.width;
                const height = rect.height;
                const radius = Math.min(width, height) / 2 - 20;

                const svg = container
                    .append("svg")
                    .attr("width", width)
                    .attr("height", height);

                const g = svg.append("g")
                    .attr("transform", `translate(${width/2},${height/2})`);

                // Create zoom behavior
                const zoom = d3.zoom()
                    .scaleExtent([0.5, 3])
                    .on("zoom", (event) => {
                        g.attr("transform", `translate(${width/2},${height/2}) ${event.transform}`);
                    });

                svg.call(zoom);

                // Store zoom for external controls
                fanChart = { svg, zoom, g, width, height };

                // Convert data to hierarchical structure
                const root = d3.hierarchy(data);

                // Create partition layout
                const partition = d3.partition()
                    .size([2 * Math.PI, radius]);

                partition(root);

                // Create arc generator
                const arc = d3.arc()
                    .startAngle(d => d.x0)
                    .endAngle(d => d.x1)
                    .innerRadius(d => d.y0)
                    .outerRadius(d => d.y1);

                // Draw segments
                const segments = g.selectAll(".fan-segment")
                    .data(root.descendants())
                    .enter()
                    .append("path")
                    .attr("class", d => `fan-segment ${getSegmentClass(d, config)}`)
                    .attr("d", arc)
                    .on("click", function(event, d) {
                        if (d.data.id) {
                            @this.call('setRootPerson', d.data.id);
                        }
                    })
                    .on("mouseover", function(event, d) {
                        showTooltip(event, d);
                    })
                    .on("mouseout", hideTooltip);

                // Add text labels
                if (config.showNames || config.showDates) {
                    const texts = g.selectAll(".fan-text-group")
                        .data(root.descendants().filter(d => d.depth > 0))
                        .enter()
                        .append("g")
                        .attr("class", "fan-text-group");

                    texts.each(function(d) {
                        const textGroup = d3.select(this);
                        const angle = (d.x0 + d.x1) / 2;
                        const radius = (d.y0 + d.y1) / 2;
                        const x = Math.sin(angle) * radius;
                        const y = -Math.cos(angle) * radius;

                        textGroup.attr("transform", `translate(${x},${y}) rotate(${angle * 180 / Math.PI - 90})`);

                        if (config.showNames && d.data.name) {
                            const nameText = textGroup.append("text")
                                .attr("class", "fan-text name")
                                .attr("text-anchor", "middle")
                                .attr("dy", config.showDates ? "-0.2em" : "0.3em");

                            // Split long names
                            const name = d.data.name;
                            if (name.length > 15) {
                                const parts = name.split(' ');
                                if (parts.length > 1) {
                                    nameText.append("tspan")
                                        .attr("x", 0)
                                        .text(parts[0]);
                                    nameText.append("tspan")
                                        .attr("x", 0)
                                        .attr("dy", "1em")
                                        .text(parts.slice(1).join(' '));
                                } else {
                                    nameText.text(name.substring(0, 12) + '...');
                                }
                            } else {
                                nameText.text(name);
                            }
                        }

                        if (config.showDates) {
                            const birthYear = d.data.birth_year || '?';
                            const deathYear = d.data.death_year || '';
                            const dateText = `${birthYear}${deathYear ? '-' + deathYear : ''}`;

                            textGroup.append("text")
                                .attr("class", "fan-text dates")
                                .attr("text-anchor", "middle")
                                .attr("dy", config.showNames ? "1em" : "0.3em")
                                .text(dateText);
                        }
                    });
                }
            }

            function getSegmentClass(d, config) {
                let className = '';

                switch (config.colorScheme) {
                    case 'generation':
                        className = `generation-${d.depth}`;
                        break;
                    case 'gender':
                        const sex = d.data.sex?.toLowerCase();
                        className = sex === 'm' ? 'gender-male' : sex === 'f' ? 'gender-female' : 'gender-unknown';
                        break;
                    case 'branch':
                        if (d.depth === 0) {
                            className = 'branch-root';
                        } else {
                            // Determine if this is paternal or maternal line
                            let current = d;
                            while (current.parent && current.parent.depth > 0) {
                                current = current.parent;
                            }
                            // First child is typically father (paternal), second is mother (maternal)
                            const isPaternal = current.parent && current.parent.children.indexOf(current) === 0;
                            className = isPaternal ? 'branch-paternal' : 'branch-maternal';
                        }
                        break;
                }

                return className;
            }

            function showTooltip(event, d) {
                const tooltip = d3.select("body").append("div")
                    .attr("class", "fan-tooltip")
                    .style("position", "absolute")
                    .style("background", "rgba(0,0,0,0.8)")
                    .style("color", "white")
                    .style("padding", "8px")
                    .style("border-radius", "4px")
                    .style("font-size", "12px")
                    .style("pointer-events", "none")
                    .style("z-index", "1000");

                // Build tooltip HTML with optional thumbnail
                const imageSrc = d.data.image || null;
                let content = '';
                if (imageSrc) {
                    content += `<div class="tooltip-content"><img src="${imageSrc}" alt="${(d.data.name||'Person')}">`;
                } else {
                    content += `<div class="tooltip-content">`;
                }

                content += `<div><strong>${d.data.name || 'Unknown'}</strong>`;
                if (d.data.birth_year || d.data.death_year) {
                    content += `<br>${d.data.birth_year || '?'} - ${d.data.death_year || ''}`;
                }
                content += `<br>Generation: ${d.depth}`;
                content += `<br>Click to expand`;
                content += `</div></div>`;

                tooltip.html(content)
                    .style("left", (event.pageX + 10) + "px")
                    .style("top", (event.pageY - 10) + "px");
            }

            function hideTooltip() {
                d3.selectAll(".fan-tooltip").remove();
            }

            function zoomIn() {
                if (fanChart) {
                    fanChart.svg.transition().call(
                        fanChart.zoom.scaleBy, 1.5
                    );
                }
            }

            function zoomOut() {
                if (fanChart) {
                    fanChart.svg.transition().call(
                        fanChart.zoom.scaleBy, 1 / 1.5
                    );
                }
            }

            function resetZoom() {
                if (fanChart) {
                    fanChart.svg.transition().call(
                        fanChart.zoom.transform,
                        d3.zoomIdentity
                    );
                }
            }
        </script>
    @endpush
</x-filament::widget>

