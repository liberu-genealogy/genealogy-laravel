<!--
File: descendant-chart.blade.php
Description: This file contains the descendant chart display and related functionality.
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descendant Chart</title>
    <script src="https://d3js.org/d3.v6.min.js"></script>
</head>
<body>
    <div id="descendant-chart-container"></div>

<x-filament::widget class="filament-descendant-chart-widget">
    <x-filament::card>
        <div id="descendant-chart-container"></div>
    </x-filament::card>

    @push('scripts')
        <script src="https://d3js.org/d3.v6.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const data = @json($descendantsData);

                /**
                 * Renders the descendant chart using the provided data.
                 *
                 * @param {Array} data - The data used to render the chart.
                 */
                function renderDescendantChart(data) {
                    if (!data || Object.keys(data).length === 0) {
                        console.warn('No valid data to render');
                        return;
                    }

                    const container = d3.select('#descendant-chart-container');
                    container.selectAll("*").remove(); // Clear existing content

                    const width = 960;
                    const height = 600;

                    const svg = container.append('svg')
                        .attr('width', width)
                        .attr('height', height)
                        .attr('class', 'shadow-lg');

                    const g = svg.append('g');

                    // Create zoom behavior
                    const zoom = d3.zoom()
                        .scaleExtent([0.1, 3])
                        .on("zoom", (event) => {
                            g.attr("transform", event.transform);
                        });

                    svg.call(zoom);

                    // Convert data to hierarchical structure
                    const root = d3.hierarchy(data, d => d.children);

                    // Create tree layout
                    const treeLayout = d3.tree().size([width - 200, height - 100]);
                    treeLayout(root);

                    // Center the tree
                    g.attr("transform", `translate(100, 50)`);

                    // Draw links
                    g.selectAll(".link")
                        .data(root.links())
                        .enter()
                        .append("path")
                        .attr("class", "link")
                        .style("fill", "none")
                        .style("stroke", "#ccc")
                        .style("stroke-width", "2px")
                        .attr("d", d3.linkVertical()
                            .x(d => d.y)
                            .y(d => d.x));

                    // Draw nodes
                    const nodes = g.selectAll(".node")
                        .data(root.descendants())
                        .enter()
                        .append("g")
                        .attr("class", "node")
                        .attr("transform", d => `translate(${d.y},${d.x})`)
                        .style("cursor", "pointer");

                    // Add circles for nodes
                    nodes.append("circle")
                        .attr("r", 30)
                        .style("fill", d => d.depth === 0 ? "#4CAF50" : "#2196F3")
                        .style("stroke", "#fff")
                        .style("stroke-width", "2px");

                    // Add text labels
                    nodes.append("text")
                        .attr("dy", "0.3em")
                        .attr("text-anchor", "middle")
                        .style("font-size", "12px")
                        .style("fill", "white")
                        .style("font-weight", "bold")
                        .text(d => {
                            const name = d.data.name || 'Unknown';
                            return name.length > 10 ? name.substring(0, 8) + '...' : name;
                        });

                    // Add generation labels
                    nodes.append("text")
                        .attr("dy", "3em")
                        .attr("text-anchor", "middle")
                        .style("font-size", "10px")
                        .style("fill", "#666")
                        .text(d => `Gen ${d.data.generation || d.depth + 1}`);
                }

                if (data && data.length > 0) {
                    renderDescendantChart(data);
                } else {
                    console.warn('No data available to render the descendant chart.');
                }
            });
        </script>
    @endpush
</x-filament::widget>
</body>
</html>



{{-- @livewire('descendant-chart-component')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descendant Chart</title>
    <script src="https://d3js.org/d3.v6.min.js"></script>
    @livewireStyles
</head>
<body>
    <div id="fanChartContainer"></div>
    <script src="{{ asset('js/fan-chart.js') }}"></script>
    @livewireScripts
</body>
</html> --}}
