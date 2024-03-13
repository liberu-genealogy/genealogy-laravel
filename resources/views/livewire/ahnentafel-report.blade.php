<!-- resources/views/livewire/ahnentafel-report.blade.php -->
<div>
    <table>
        <thead>
            <tr>
                <th>Ahnentafel Number</th>
                <th>Name</th>
                <th>Birth Date</th>
                <th>Death Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $person)
                <tr>
                    <td>{{ $person['number'] }}</td>
                    <td>{{ $person['name'] }}</td>
                    <td>{{ $person['birth'] }}</td>
                    <td>{{ $person['death'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
