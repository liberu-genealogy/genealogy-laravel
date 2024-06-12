<div>
    <form wire:submit.prevent="generateReport">
        <div class="flex">
            {{ $this->form }}
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </div>
    </form>

    <div wire:loading>
        Generating report...
    </div>

    @if(!empty($reportData))
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>d'Aboville Number</th>
                    <th>Name</th>
                    <th>Birth Date</th>
                    <th>Death Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{ $data['number'] }}</td>
                        <td>{{ $data['name'] }}</td>
                        <td>{{ $data['birth'] ?? 'N/A' }}</td>
                        <td>{{ $data['death'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
