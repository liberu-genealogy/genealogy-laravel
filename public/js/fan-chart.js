/**
 * Fan Chart JavaScript Library
 * Enhanced genealogy fan chart with D3.js
 */

class FanChart {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.options = {
            width: 800,
            height: 600,
            innerRadius: 50,
            showNames: true,
            showDates: false,
            colorScheme: 'generation',
            generations: 5,
            ...options
        };
        
        this.svg = null;
        this.g = null;
        this.zoom = null;
        this.data = null;
    }

    render(data) {
        this.data = data;
        this.clear();
        this.createSvg();
        this.renderChart();
    }

    clear() {
        d3.select(`#${this.containerId}`).selectAll("*").remove();
    }

    createSvg() {
        const container = d3.select(`#${this.containerId}`);
        const containerNode = container.node();
        const rect = containerNode.getBoundingClientRect();
        
        this.options.width = rect.width || this.options.width;
        this.options.height = rect.height || this.options.height;
        
        const radius = Math.min(this.options.width, this.options.height) / 2 - 20;

        this.svg = container
            .append("svg")
            .attr("width", this.options.width)
            .attr("height", this.options.height);

        this.g = this.svg.append("g")
            .attr("transform", `translate(${this.options.width/2},${this.options.height/2})`);

        // Add zoom behavior
        this.zoom = d3.zoom()
            .scaleExtent([0.5, 3])
            .on("zoom", (event) => {
                this.g.attr("transform", 
                    `translate(${this.options.width/2},${this.options.height/2}) ${event.transform}`
                );
            });

        this.svg.call(this.zoom);
    }

    renderChart() {
        if (!this.data) return;

        const radius = Math.min(this.options.width, this.options.height) / 2 - 20;
        
        // Convert data to hierarchical structure
        const root = d3.hierarchy(this.data);
        
        // Create partition layout
        const partition = d3.partition()
            .size([2 * Math.PI, radius]);

        partition(root);

        // Create arc generator
        const arc = d3.arc()
            .startAngle(d => d.x0)
            .endAngle(d => d.x1)
            .innerRadius(d => Math.max(this.options.innerRadius, d.y0))
            .outerRadius(d => d.y1);

        // Draw segments
        this.g.selectAll(".fan-segment")
            .data(root.descendants())
            .enter()
            .append("path")
            .attr("class", d => `fan-segment ${this.getSegmentClass(d)}`)
            .attr("d", arc)
            .style("fill", d => this.getSegmentColor(d))
            .style("stroke", "#fff")
            .style("stroke-width", 1)
            .style("cursor", "pointer")
            .on("click", (event, d) => this.onSegmentClick(event, d))
            .on("mouseover", (event, d) => this.showTooltip(event, d))
            .on("mouseout", () => this.hideTooltip());

        // Add text labels
        this.addTextLabels(root.descendants().filter(d => d.depth > 0));
    }

    addTextLabels(nodes) {
        if (!this.options.showNames && !this.options.showDates) return;

        const textGroups = this.g.selectAll(".fan-text-group")
            .data(nodes)
            .enter()
            .append("g")
            .attr("class", "fan-text-group");

        textGroups.each((d, i, nodes) => {
            const textGroup = d3.select(nodes[i]);
            const angle = (d.x0 + d.x1) / 2;
            const radius = (d.y0 + d.y1) / 2;
            const x = Math.sin(angle) * radius;
            const y = -Math.cos(angle) * radius;

            textGroup.attr("transform", `translate(${x},${y}) rotate(${angle * 180 / Math.PI - 90})`);

            if (this.options.showNames && d.data.name) {
                this.addNameText(textGroup, d);
            }

            if (this.options.showDates) {
                this.addDateText(textGroup, d);
            }
        });
    }

    addNameText(textGroup, d) {
        const nameText = textGroup.append("text")
            .attr("class", "fan-text name")
            .attr("text-anchor", "middle")
            .attr("dy", this.options.showDates ? "-0.2em" : "0.3em")
            .style("font-size", "11px")
            .style("font-weight", "600")
            .style("fill", "#1f2937");

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

    addDateText(textGroup, d) {
        const birthYear = d.data.birth_year || '?';
        const deathYear = d.data.death_year || '';
        const dateText = `${birthYear}${deathYear ? '-' + deathYear : ''}`;
        
        textGroup.append("text")
            .attr("class", "fan-text dates")
            .attr("text-anchor", "middle")
            .attr("dy", this.options.showNames ? "1em" : "0.3em")
            .style("font-size", "9px")
            .style("fill", "#6b7280")
            .text(dateText);
    }

    getSegmentClass(d) {
        return `generation-${d.depth}`;
    }

    getSegmentColor(d) {
        switch (this.options.colorScheme) {
            case 'generation':
                const colors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444', '#ec4899', '#06b6d4', '#84cc16'];
                return colors[d.depth % colors.length];
            
            case 'gender':
                const sex = d.data.sex?.toLowerCase();
                return sex === 'm' ? '#3b82f6' : sex === 'f' ? '#ec4899' : '#6b7280';
            
            case 'branch':
                if (d.depth === 0) return '#10b981';
                let current = d;
                while (current.parent && current.parent.depth > 0) {
                    current = current.parent;
                }
                const isPaternal = current.parent && current.parent.children.indexOf(current) === 0;
                return isPaternal ? '#3b82f6' : '#ec4899';
            
            default:
                return '#3b82f6';
        }
    }

    onSegmentClick(event, d) {
        if (this.options.onPersonClick && d.data.id) {
            this.options.onPersonClick(d.data.id);
        }
    }

    showTooltip(event, d) {
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

        let content = `<strong>${d.data.name || 'Unknown'}</strong>`;
        if (d.data.birth_year || d.data.death_year) {
            content += `<br>${d.data.birth_year || '?'} - ${d.data.death_year || ''}`;
        }
        content += `<br>Generation: ${d.depth}`;
        content += `<br>Click to expand`;

        tooltip.html(content)
            .style("left", (event.pageX + 10) + "px")
            .style("top", (event.pageY - 10) + "px");
    }

    hideTooltip() {
        d3.selectAll(".fan-tooltip").remove();
    }

    zoomIn() {
        this.svg.transition().call(this.zoom.scaleBy, 1.5);
    }

    zoomOut() {
        this.svg.transition().call(this.zoom.scaleBy, 1 / 1.5);
    }

    resetZoom() {
        this.svg.transition().call(this.zoom.transform, d3.zoomIdentity);
    }

    updateOptions(newOptions) {
        this.options = { ...this.options, ...newOptions };
        if (this.data) {
            this.render(this.data);
        }
    }
}

// Global functions for backward compatibility
function initializeFanChart(data, options = {}) {
    const chart = new FanChart('fanChartContainer', options);
    chart.render(data);
    return chart;
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FanChart;
}