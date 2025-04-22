

<div class="family-tree-builder">
    <div class="toolbar">
        <button wire:click="$emit('addNewPerson')" class="btn btn-primary">
            Add Person
        </button>
    </div>

    <div id="tree-container" class="tree-container">
        @foreach($treeData as $person)
            <div class="person-node" 
                 data-id="{{ $person['id'] }}"
                 style="left: {{ $person['position']['x'] }}px; top: {{ $person['position']['y'] }}px;">
                <div class="person-content">
                    <h4>{{ $person['name'] }}</h4>
                    <div class="person-actions">
                        <button wire:click="$emit('editPerson', {{ $person['id'] }})"
                                class="btn btn-sm btn-secondary">
                            Edit
                        </button>
                        <button wire:click="removePerson({{ $person['id'] }})"
                                class="btn btn-sm btn-danger">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .family-tree-builder {
        position: relative;
        width: 100%;
        height: 800px;
        overflow: auto;
    }

    .tree-container {
        position: relative;
        width: 3000px;
        height: 2000px;
    }

    .person-node {
        position: absolute;
        width: 200px;
        padding: 10px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: move;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script>
document.addEventListener('livewire:load', function () {
    interact('.person-node').draggable({
        inertia: true,
        modifiers: [
            interact.modifiers.restrictRect({
                restriction: 'parent',
                endOnly: true
            })
        ],
        autoScroll: true,
        listeners: {
            move: dragMoveListener,
            end: dragEndListener
        }
    });

    function dragMoveListener(event) {
        const target = event.target;
        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

        target.style.transform = `translate(${x}px, ${y}px)`;
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
    }

    function dragEndListener(event) {
        const target = event.target;
        const personId = target.getAttribute('data-id');
        const x = parseFloat(target.getAttribute('data-x')) || 0;
        const y = parseFloat(target.getAttribute('data-y')) || 0;

        @this.call('updatePersonPosition', personId, x, y);
    }
});
</script>
@endpush