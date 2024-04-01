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

    @livewire('descendant-chart-component')

    <script>
        document.addEventListener('livewire:load', function () {
            const data = @json($descendantsData);

            /**
 * Renders the descendant chart using the provided data.
 *
 * @param {Array} data - The data used to render the chart.
 */
/**
 * Renders the descendant chart using the provided data.
 *
 * @param {Array} data - The data used to render the chart.
 */
function renderDescendantChart(data) {
                const container = d3.select('#descendant-chart-container');
                const width = 960;
                const height = 500;

                const svg = container.append('svg')
                    .attr('width', width)
                    .attr('height', height)
                    .attr('class', 'shadow-lg');

                svg.selectAll('circle')
                    .data(data)
                    .enter()
                    .append('circle')
                    .attr('cx', (d, i) => i * 100 + 50)
                    .attr('cy', height / 2)
                    .attr('r', 40)
                    .attr('class', 'fill-current text-blue-500');
            }

            renderDescendantChart(data);
        });
    </script>
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
