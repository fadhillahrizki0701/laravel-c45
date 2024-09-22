<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Usia</th>
            <th>Berat Badan per Usia</th>
            <th>Tinggi Badan per Usia</th>
            <th>Berat Badan per Tinggi Badan</th>
            <th>Predicted Label</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
            <tr>
                <td>{{ $result['nama'] }}</td>
                <td>{{ $result['usia'] }}</td>
                <td>{{ $result['berat_badan_per_usia'] }}</td>
                <td>{{ $result['tinggi_badan_per_usia'] }}</td>
                <td>{{ $result['berat_badan_per_tinggi_badan'] }}</td>
                <td>{{ $result['predicted_label'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
