```html
<div>
    <!-- Include vis-timeline CSS + JS from CDN -->
    <link href="https://unpkg.com/vis-timeline@latest/styles/vis-timeline-graph2d.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js"></script>

    <div class="filament-timeline-controls" style="margin-bottom:0.5rem;">
        <button type="button" onclick="timelineZoomOut()" class="filament-button">-</button>
        <button type="button" onclick="timelineZoomIn()" class="filament-button">+</button>
        <button type="button" onclick="timelineNavigate(-1)" class="filament-button">◀</button>
        <button type="button" onclick="timelineNavigate(1)" class="filament-button">▶</button>
    </div>

    <div id="timeline" style="height: 400px; border: 1px solid var(--tw-border-opacity, #e5e7eb);"></div>

    <!-- Modal -->
    <div wire:ignore.self id="timeline-modal" style="display: {{ $selectedEvent ? 'block' : 'none' }}; position:fixed; left:50%; top:20%; transform:translateX(-50%); z-index:10000; background:#fff; padding:1rem; border-radius:6px; box-shadow:0 6px 18px rgba(0,0,0,.2); max-width:600px;">
        @if($selectedEvent)
            <h3>{{ $selectedEvent['title'] }}</h3>
            <p><strong>Date:</strong> {{ $selectedEvent['start'] }}</p>
            @if(isset($selectedEvent['place']))
                <p><strong>Place:</strong> {{ $selectedEvent['place'] }}</p>
            @endif
            @if(isset($selectedEvent['country']))
                <p><strong>Country:</strong> {{ $selectedEvent['country'] }}</p>
            @endif
            @if(isset($selectedEvent['description']))
                <p>{{ $selectedEvent['description'] }}</p>
            @endif
            @if(isset($selectedEvent['source_url']) && $selectedEvent['source_url'])
                <p><a href="{{ $selectedEvent['source_url'] }}" target="_blank" rel="noopener">Source</a></p>
            @endif
            <div style="text-align:right;">
                <button onclick="Livewire.emit('closeModal')" wire:click="closeModal()" class="filament-button">Close</button>
            </div>
        @endif
    </div>

    <script>
        (function () {
            const itemsData = @json($events);

            // Convert items into vis DataSet
            const items = new vis.DataSet(itemsData.map(item => ({
                id: item.id,
                content: item.content,
                start: item.start,
                group: item.group,
                type: 'point',
                // Add a className by type so you can style family vs historical differently
                className: item.type === 'family' ? 'timeline-family-event' : 'timeline-historical-event',
            })));

            const container = document.getElementById('timeline');

            const groups = new vis.DataSet([
                { id: 'family', content: 'Family events' },
                { id: 'historical', content: 'Historical events' },
            ]);

            const options = {
                stack: false,
                showMajorLabels: true,
                showCurrentTime: true,
                zoomMin: 1000 * 60 * 60 * 24 * 30, // ~1 month
                zoomMax: 1000 * 60 * 60 * 24 * 365 * 1000, // very large
                orientation: 'bottom',
                tooltip: {
                    followMouse: true
                }
            };

            window._timeline = window._timeline || {};
            // create or overwrite
            if (window._timeline.instance) {
                window._timeline.instance.setItems(items);
            } else {
                window._timeline.instance = new vis.Timeline(container, items, groups, options);
            }

            // click handler -> notify Livewire
            window._timeline.instance.on('itemclick', function (props) {
                if (props.item) {
                    Livewire.emit('timelineItemClicked', props.item.toString());
                }
            });

            window.timelineZoomIn = function () {
                const tl = window._timeline.instance;
                const range = tl.getWindow();
                const start = new Date(range.start);
                const end = new Date(range.end);
                const diff = end - start;
                const newStart = new Date(start.getTime() + diff * 0.15);
                const newEnd = new Date(end.getTime() - diff * 0.15);
                tl.setWindow(newStart, newEnd, { animation: { duration: 300 } });
            };

            window.timelineZoomOut = function () {
                const tl = window._timeline.instance;
                const range = tl.getWindow();
                const start = new Date(range.start);
                const end = new Date(range.end);
                const diff = end - start;
                const newStart = new Date(start.getTime() - diff * 0.15);
                const newEnd = new Date(end.getTime() + diff * 0.15);
                tl.setWindow(newStart, newEnd, { animation: { duration: 300 } });
            };

            window.timelineNavigate = function (dir) {
                const tl = window._timeline.instance;
                const range = tl.getWindow();
                const start = new Date(range.start);
                const end = new Date(range.end);
                const diff = end - start;
                const shift = diff * 0.5 * dir;
                tl.setWindow(new Date(start.getTime() + shift), new Date(end.getTime() + shift), { animation: { duration: 300 } });
            };

            // Wire up Livewire to show/hide modal when selectedEvent changes
            Livewire.hook('message.processed', (message, component) => {
                // After Livewire updates, show/hide modal based on presence of selectedEvent
                @this.on('selectedEventChanged', () => {});
                // simple DOM toggle handled by server-rendered inline style
            });
        })();
    </script>

    <style>
        .timeline-family-event {
            background-color: #0ea5a0; /* teal */
            color: #fff;
            border-color: #0ea5a0;
        }
        .timeline-historical-event {
            background-color: #2563eb; /* blue */
            color: #fff;
            border-color: #2563eb;
        }
    </style>
</div>
