import * as d3 from 'd3';

async function fetchDescendantData() {
    const response = await fetch('/api/descendants');
    if (!response.ok) {
        throw new Error('Failed to fetch descendant data');
    }
    return response.json();
}

function processDescendantData(data) {
    // Process data to fit D3.js structure
    return data; // Placeholder for processed data
}

function renderDescendantChart(data) {
    const container = d3.select('#descendant-chart-container');
    const width = 960;
    const height = 500;

    const svg = container.append('svg')
        .attr('width', width)
        .attr('height', height)
        .attr('class', 'shadow-lg');

    // Example of creating a chart with D3.js and Tailwind CSS
    svg.selectAll('circle')
        .data(data)
        .enter()
        .append('circle')
        .attr('cx', (d, i) => i * 100 + 50)
        .attr('cy', height / 2)
        .attr('r', 40)
        .attr('class', 'fill-current text-blue-500');
}

async function initDescendantChart() {
    try {
        const rawData = await fetchDescendantData();
        const data = processDescendantData(rawData);
        renderDescendantChart(data);
    } catch (error) {
        console.error('Error initializing descendant chart:', error);
    }
}

initDescendantChart();
