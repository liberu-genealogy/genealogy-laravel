<x-filament::widget class="filament-descendant-chart-widget">
    <x-filament::card>
        <div class="descendant-chart-header">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Descendant Chart</h3>
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
                    <select wire:model.live="layout" class="rounded border-gray-300 text-sm">
                        <option value="vertical">Vertical Tree</option>
                        <option value="horizontal">Horizontal Tree</option>
                        <option value="compact">Compact View</option>
                    </select>
                    <button wire:click="toggleSpouses" class="px-3 py-1 text-sm rounded {{ $showSpouses ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        Spouses
                    </button>
                    <button wire:click="toggleDates" class="px-3 py-1 text-sm rounded {{ $showDates ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                        Dates
                    </button>
                    <select wire:model.live="colorScheme" class="rounded border-gray-300 text-sm">
                        <option value="generation">By Generation</option>
                        <option value="gender">By Gender</option>
                        <option value="family">By Family</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="descendant-chart-container" class="descendant-chart-container">
            @if($descendantData)
                <div class="descendant-chart-wrapper">
                    <div id="descendantChartSvg"></div>
                    <div class="descendant-chart-controls">
                        <button onclick="zoomInDescendant()" class="control-btn" title="Zoom In">+</button>
                        <button onclick="zoomOutDescendant()" class="control-btn" title="Zoom Out">-</button>
                        <button onclick="resetZoomDescendant()" class="control-btn" title="Reset">⌂</button>
                        <button onclick="fitToScreen()" class="control-btn" title="Fit to Screen">⊞</button>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No data available to display the descendant chart.</p>
                    <p class="text-sm mt-2">Please select a person to start building the chart.</p>
                </div>
            @endif
        </div>

        @if($rootPerson)
            <div class="mt-4 text-sm text-gray-600">
                <p><strong>Root Person:</strong> {{ $rootPerson->fullname() }}</p>
                <p><strong>Generations:</strong> {{ $generations }}</p>
                <p><strong>Layout:</strong> {{ ucfirst($layout) }}</p>
                <p><strong>Color Scheme:</strong> {{ ucfirst($colorScheme) }}</p>
            </div>
        @endif
    </x-filament::card>

    @push('styles')
        <style>
            .descendant-chart-container {
                position: relative;
                min-height: 600px;
                background: #f8fafc;
                border-radius: 8px;
                overflow: hidden;
            }

            .descendant-chart-wrapper {
                position: relative;
                width: 100%;
                height: 600px;
            }

            #descendantChartSvg {
                width: 100%;
                height: 100%;
                cursor: grab;
            }

            #descendantChartSvg:active {
                cursor: grabbing;
            }

            .descendant-chart-controls {
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

            .person-node {
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .person-node:hover {
                transform: scale(1.05);
            }

            .person-box {
                fill: white;
                stroke: #e2e8f0;
                stroke-width: 2;
                rx: 8;
                ry: 8;
            }

            .person-box.male {
                stroke: #3b82f6;
                fill: #eff6ff;
            }

            .person-box.female {
                stroke: #ec4899;
                fill: #fdf2f8;
            }

            .person-box.root {
                stroke: #10b981;
                fill: #f0fdf4;
                stroke-width: 3;
            }

            .person-text {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-size: 12px;
                fill: #1f2937;
                text-anchor: middle;
                pointer-events: none;
            }

            .person-name {
                font-weight: 600;
                font-size: 13px;
            }

            .person-dates {
                font-size: 10px;
                fill: #6b7280;
            }

            .spouse-box {
                fill: #fef3c7;
                stroke: #f59e0b;
                stroke-width: 1;
                rx: 6;
                ry: 6;
            }

            .connection-line {
                stroke: #9ca3af;
                stroke-width: 2;
                fill: none;
            }

            .family-line {
                stroke: #6b7280;
                stroke-width: 1;
                fill: none;
                stroke-dasharray: 3,3;
            }

            .generation-1 .person-box { fill: #f0f9ff; stroke: #0ea5e9; }
            .generation-2 .person-box { fill: #f0fdf4; stroke: #22c55e; }
            .generation-3 .person-box { fill: #fef3c7; stroke: #f59e0b; }
            .generation-4 .person-box { fill: #fce7f3; stroke: #ec4899; }
            .generation-5 .person-box { fill: #f3e8ff; stroke: #a855f7; }
            .generation-6 .person-box { fill: #fef2f2; stroke: #ef4444; }
            .generation-7 .person-box { fill: #f0fdfa; stroke: #14b8a6; }
            .generation-8 .person-box { fill: #fffbeb; stroke: #f97316; }

            .expand-btn {
                fill: #3b82f6;
                stroke: white;
                stroke-width: 2;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .expand-btn:hover {
                fill: #2563eb;
                transform: scale(1.1);
            }

            @media (max-width: 768px) {
                .descendant-chart-container {
                    min-height: 400px;
                }
                
                .descendant-chart-wrapper {
                    height: 400px;
                }
                
                .descendant-chart-header .flex {
                    flex-direction: column;
                    gap: 2;
                }
            }
        </style>
    @endpush>

    @push('scripts')
        <script src="https://d3js.org/d3.v7.min.js"></script>
        <script>
            let descendantChart = null;
            let currentDescendantData = null;
            let currentDescendantConfig = null;

            document.addEventListener('livewire:init', () => {
                initializeDescendantChart();
                
                Livewire.on('refreshDescendantChart', () => {
                    setTimeout(() => {
                        initializeDescendantChart();
                    }, 100);
                });
            });

            function initializeDescendantChart() {
                const descendantData = @json($descendantData);
                const config = {
                    showSpouses: @json($showSpouses),
                    showDates: @json($showDates),
                    layout: @json($layout),
                    colorScheme: @json($colorScheme),
                    generations: @json($generations)
                };

                if (!descendantData || Object.keys(descendantData).length === 0) {
                    console.warn('No data available to render the descendant chart.');
                    return;
                }

                currentDescendantData = descendantData;
                currentDescendantConfig = config;
                renderDescendantChart(descendantData, config);
            }

            function renderDescendantChart(data, config) {
                // Clear existing chart
                d3.select("#descendantChartSvg").selectAll("*").remove();

                const container = d3.select("#descendantChartSvg");
                const containerNode = container.node();
                const rect = containerNode.getBoundingClientRect();
                const width = rect.width;
                const height = rect.height;

                const svg = container
                    .append("svg")
                    .attr("width", width)
                    .attr("height", height);

                const g = svg.append("g");

                // Create zoom behavior
                const zoom = d3.zoom()
                    .scaleExtent([0.1, 3])
                    .on("zoom", (event) => {
                        g.attr("transform", event.transform);
                    });

                svg.call(zoom);

                // Store chart for external controls
                descendantChart = { svg, zoom, g, width, height };

                // Convert data to hierarchical structure
                const root = d3.hierarchy(data, d => d.children);

                // Create tree layout based on configuration
                let treeLayout;
                if (config.layout === 'horizontal') {
                    treeLayout = d3.tree().size([height - 100, width - 200]);
                } else {
                    treeLayout = d3.tree().size([width - 200, height - 100]);
                }

                treeLayout(root);

                // Adjust positions based on layout
                if (config.layout === 'horizontal') {
                    root.descendants().forEach(d => {
                        const temp = d.x;
                        d.x = d.y;
                        d.y = temp;
                    });
                }

                // Center the tree
                const centerX = width / 2;
                const centerY = height / 2;
                
                if (config.layout === 'horizontal') {
                    g.attr("transform", `translate(50, ${centerY - root.x})`);
                } else {
                    g.attr("transform", `translate(${centerX - root.y}, 50)`);
                }

                // Draw links
                const links = g.selectAll(".connection-line")
                    .data(root.links())
                    .enter()
                    .append("path")
                    .attr("class", "connection-line")
                    .attr("d", d => {
                        if (config.layout === 'horizontal') {
                            return `M${d.source.x},${d.source.y} 
                                   C${(d.source.x + d.target.x) / 2},${d.source.y} 
                                   ${(d.source.x + d.target.x) / 2},${d.target.y} 
                                   ${d.target.x},${d.target.y}`;
                        } else {
                            return `M${d.source.y},${d.source.x} 
                                   C${d.source.y},${(d.source.x + d.target.x) / 2} 
                                   ${d.target.y},${(d.source.x + d.target.x) / 2} 
                                   ${d.target.y},${d.target.x}`;
                        }
                    });

                // Draw nodes
                const nodes = g.selectAll(".person-node")
                    .data(root.descendants())
                    .enter()
                    .append("g")
                    .attr("class", d => `person-node generation-${d.data.generation}`)
                    .attr("transform", d => {
                        if (config.layout === 'horizontal') {
                            return `translate(${d.x},${d.y})`;
                        } else {
                            return `translate(${d.y},${d.x})`;
                        }
                    })
                    .on("click", function(event, d) {
                        if (d.data.id) {
                            @this.call('expandPerson', d.data.id);
                        }
                    })
                    .on("mouseover", function(event, d) {
                        showDescendantTooltip(event, d);
                    })
                    .on("mouseout", hideDescendantTooltip);

                // Add person boxes
                nodes.append("rect")
                    .attr("class", d => {
                        let classes = "person-box";
                        if (d.depth === 0) classes += " root";
                        else if (d.data.sex === 'M') classes += " male";
                        else if (d.data.sex === 'F') classes += " female";
                        return classes;
                    })
                    .attr("x", -60)
                    .attr("y", -25)
                    .attr("width", 120)
                    .attr("height", 50);

                // Add expand buttons for nodes with children
                nodes.filter(d => d.children && d.children.length > 0)
                    .append("circle")
                    .attr("class", "expand-btn")
                    .attr("cx", 0)
                    .attr("cy", 35)
                    .attr("r", 8)
                    .on("click", function(event, d) {
                        event.stopPropagation();
                        if (d.data.id) {
                            @this.call('expandPerson', d.data.id);
                        }
                    });

                // Add expand button text
                nodes.filter(d => d.children && d.children.length > 0)
                    .append("text")
                    .attr("x", 0)
                    .attr("y", 39)
                    .attr("text-anchor", "middle")
                    .attr("font-size", "10px")
                    .attr("fill", "white")
                    .attr("pointer-events", "none")
                    .text("↓");

                // Add person names
                nodes.append("text")
                    .attr("class", "person-text person-name")
                    .attr("dy", config.showDates ? "-0.2em" : "0.3em")
                    .text(d => {
                        const name = d.data.name || 'Unknown';
                        return name.length > 15 ? name.substring(0, 12) + '...' : name;
                    });

                // Add dates if enabled
                if (config.showDates) {
                    nodes.append("text")
                        .attr("class", "person-text person-dates")
                        .attr("dy", "1em")
                        .text(d => {
                            const birthYear = d.data.birth_year || '?';
                            const deathYear = d.data.death_year || '';
                            return `${birthYear}${deathYear ? '-' + deathYear : ''}`;
                        });
                }

                // Add spouse information if enabled
                if (config.showSpouses) {
                    addSpouseNodes(g, root.descendants(), config);
                }
            }

            function addSpouseNodes(g, nodes, config) {
                nodes.forEach(node => {
                    if (node.data.families) {
                        node.data.families.forEach((family, familyIndex) => {
                            if (family.spouse) {
                                const spouseX = config.layout === 'horizontal' ? node.x : node.y;
                                const spouseY = config.layout === 'horizontal' ? node.y + 80 : node.x + 80;

                                const spouseGroup = g.append("g")
                                    .attr("class", "spouse-node")
                                    .attr("transform", `translate(${spouseX},${spouseY})`);

                                spouseGroup.append("rect")
                                    .attr("class", "spouse-box")
                                    .attr("x", -50)
                                    .attr("y", -20)
                                    .attr("width", 100)
                                    .attr("height", 40);

                                spouseGroup.append("text")
                                    .attr("class", "person-text")
                                    .attr("dy", "0.3em")
                                    .text(family.spouse.name || 'Unknown Spouse');

                                // Draw connection line to spouse
                                g.append("path")
                                    .attr("class", "family-line")
                                    .attr("d", `M${config.layout === 'horizontal' ? node.x : node.y},${config.layout === 'horizontal' ? node.y : node.x} 
                                               L${spouseX},${spouseY}`);
                            }
                        });
                    }
                });
            }

            function showDescendantTooltip(event, d) {
                const tooltip = d3.select("body").append("div")
                    .attr("class", "descendant-tooltip")
                    .style("position", "absolute")
                    .style("background", "rgba(0,0,0,0.8)")
                    .style("color", "white")
                    .style("padding", "8px")
                    .style("border-radius", "4px")
                    .style("font-size", "12px")
                    .style("pointer-events", "none")
                    .style("z-index", "1000");

                let content = `<strong>${d.data.name || 'Unknown'}</strong>`;
                if (d.data.birth_year || d.data.death_year) {
                    content += `<br>${d.data.birth_year || '?'} - ${d.data.death_year || ''}`;
                }
                content += `<br>Generation: ${d.data.generation}`;
                if (d.children && d.children.length > 0) {
                    content += `<br>Children: ${d.children.length}`;
                }
                content += `<br>Click to expand`;

                tooltip.html(content)
                    .style("left", (event.pageX + 10) + "px")
                    .style("top", (event.pageY - 10) + "px");
            }

            function hideDescendantTooltip() {
                d3.selectAll(".descendant-tooltip").remove();
            }

            function zoomInDescendant() {
                if (descendantChart) {
                    descendantChart.svg.transition().call(
                        descendantChart.zoom.scaleBy, 1.5
                    );
                }
            }

            function zoomOutDescendant() {
                if (descendantChart) {
                    descendantChart.svg.transition().call(
                        descendantChart.zoom.scaleBy, 1 / 1.5
                    );
                }
            }

            function resetZoomDescendant() {
                if (descendantChart) {
                    descendantChart.svg.transition().call(
                        descendantChart.zoom.transform,
                        d3.zoomIdentity
                    );
                }
            }

            function fitToScreen() {
                if (descendantChart && currentDescendantData) {
                    // Calculate bounds and fit to screen
                    const bounds = descendantChart.g.node().getBBox();
                    const fullWidth = descendantChart.width;
                    const fullHeight = descendantChart.height;
                    const width = bounds.width;
                    const height = bounds.height;
                    const midX = bounds.x + width / 2;
                    const midY = bounds.y + height / 2;
                    
                    const scale = Math.min(fullWidth / width, fullHeight / height) * 0.9;
                    const translate = [fullWidth / 2 - scale * midX, fullHeight / 2 - scale * midY];
                    
                    descendantChart.svg.transition().call(
                        descendantChart.zoom.transform,
                        d3.zoomIdentity.translate(translate[0], translate[1]).scale(scale)
                    );
                }
            }
        </script>
    @endpush
</x-filament::widget>