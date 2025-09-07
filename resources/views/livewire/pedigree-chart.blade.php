<div class="pedigree-chart-container">
    <div class="chart-header mb-4">
        <h3 class="text-xl font-semibold text-gray-800">Pedigree Chart</h3>
        <div class="chart-controls flex gap-2 mt-2">
            <button wire:click="setGenerations(3)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 3 ? 'bg-blue-700' : '' }}">3 Gen</button>
            <button wire:click="setGenerations(4)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 4 ? 'bg-blue-700' : '' }}">4 Gen</button>
            <button wire:click="setGenerations(5)" class="px-3 py-1 bg-blue-500 text-white rounded {{ $generations == 5 ? 'bg-blue-700' : '' }}">5 Gen</button>
            <button wire:click="toggleDates" class="px-3 py-1 bg-gray-500 text-white rounded {{ $showDates ? 'bg-gray-700' : '' }}">{{ $showDates ? 'Hide' : 'Show' }} Dates</button>
        </div>
    </div>

    <div id="pedigree-chart-display" class="chart-display bg-white border rounded-lg p-4" style="min-height: 500px;">
        @if(!empty($tree))
            <div class="pedigree-tree">
                {!! $this->renderPedigreeTree($tree) !!}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">👥</div>
                <h4 class="text-lg font-medium text-gray-600 mb-2">No Family Data Available</h4>
                <p class="text-gray-500">Add people to your family tree to see the pedigree chart.</p>
            </div>
        @endif
    </div>
</div>

<style>
.pedigree-tree {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: Arial, sans-serif;
}

.generation-level {
    display: flex;
    justify-content: center;
    margin: 20px 0;
    position: relative;
}

.person-box {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    margin: 0 10px;
    min-width: 150px;
    text-align: center;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.person-box:hover {
    background: #e9ecef;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.person-box.male {
    border-color: #007bff;
    background: #e3f2fd;
}

.person-box.female {
    border-color: #e91e63;
    background: #fce4ec;
}

.person-name {
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 4px;
    color: #333;
}

.person-dates {
    font-size: 12px;
    color: #666;
}

.expand-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    cursor: pointer;
    display: none;
}

.person-box:hover .expand-btn {
    display: block;
}

.parents-container {
    display: flex;
    justify-content: space-around;
    width: 100%;
    margin-top: 20px;
}

.parent-branch {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.father-branch {
    margin-right: 10px;
}

.mother-branch {
    margin-left: 10px;
}

.empty-person-box {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    color: #6c757d;
    font-style: italic;
}

.connection-line {
    position: absolute;
    top: -10px;
    left: 50%;
    width: 2px;
    height: 20px;
    background: #ccc;
    transform: translateX(-50%);
}

@media (max-width: 768px) {
    .person-box {
        min-width: 120px;
        padding: 8px;
        margin: 0 5px;
    }

    .person-name {
        font-size: 12px;
    }

    .person-dates {
        font-size: 10px;
    }
}
</style>

<script>
function expandPerson(personId) {
    @this.call('expandPerson', personId);
}

document.addEventListener('livewire:init', () => {
    Livewire.on('refreshChart', () => {
        console.log('Pedigree chart refreshed');
    });
});
</script>
