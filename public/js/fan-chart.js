document.addEventListener('DOMContentLoaded', function() {
    const svg = d3.select('#fanChartContainer').append('svg')
        .attr('width', '100%')
        .attr('height', 500)
        .append('g')
        .attr('transform', 'translate(60,60)');

    fetch('/api/people')
        .then(response => response.json())
        .then(data => {
            const root = d3.hierarchy(data, d => d.children);
            const fanChart = d3.cluster().size([2 * Math.PI, 250])(root);

            const link = svg.selectAll('.link')
                .data(fanChart.links())
                .enter().append('path')
                .attr('class', 'link')
                .attr('d', d3.linkRadial()
                    .angle(d => d.x)
                    .radius(d => d.y));

            const node = svg.selectAll('.node')
                .data(fanChart.descendants())
                .enter().append('g')
                .attr('class', 'node')
                .attr('transform', d => `rotate(${d.x * 180 / Math.PI - 90})translate(${d.y},0)`);

            node.append('circle')
                .attr('r', 4.5);

            node.append('text')
                .attr('dy', '0.31em')
                .attr('x', d => d.x < Math.PI === !d.children ? 6 : -6)
                .attr('text-anchor', d => d.x < Math.PI === !d.children ? 'start' : 'end')
                .attr('transform', d => d.x >= Math.PI ? 'rotate(180)' : null)
                .text(d => d.data.name)
                .clone(true).lower()
                .attr('stroke', 'white');
        })
        .catch(error => console.error('Error fetching data:', error));
});
