<!-- resources/views/test_data.blade.php -->
<form action="{{ route('testModels') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file">Impor data dari Excel</label>
    <div class="input-group my-3">
        <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx">
        <button type="submit" class="btn btn-success">Unggah Data</button>
    </div>
</form>





<form action="{{ route('testModel') }}" method="POST">
    @csrf
    <label for="usia">Usia:</label>
    <select name="usia" required>
        <option value="Fase 1">Fase 1</option>
        <option value="Fase 2">Fase 2</option>
        <option value="Fase 3">Fase 3</option>
        <option value="Fase 4">Fase 4</option>
    </select>

    <label for="berat_badan_per_usia">Berat Badan per Usia:</label>
    <select name="berat_badan_per_usia" required>
        <option value="Normal">Normal</option>
        <option value="Kurang">Kurang</option>
        <option value="Sangat Kurang">Sangat Kurang</option>
    </select>

    <label for="tinggi_badan_per_usia">Tinggi Badan per Usia:</label>
    <select name="tinggi_badan_per_usia" required>
        <option value="Normal">Normal</option>
        <option value="Pendek">Pendek</option>
        <option value="Sangat Pendek">Sangat Pendek</option>
    </select>

    <button type="submit">Hasil klasifikasi</button>
</form>


<h2>Hasil Klasifikasi</h2>

<p>Usia: {{ $newData['usia'] }}</p>
<p>Berat Badan per Usia: {{ $newData['berat_badan_per_usia'] }}</p>
<p>Tinggi Badan per Usia: {{ $newData['tinggi_badan_per_usia'] }}</p>

<h3>Hasil Klasifikasi: {{ $predictedLabel }}</h3>

