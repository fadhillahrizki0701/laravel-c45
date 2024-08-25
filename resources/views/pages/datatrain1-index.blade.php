@extends('layouts.app')

@section('title', 'Datatrain 1')

@section('content')
<section class="container p-4">
    <h2 class="mb-4" style="color:#435EBE">Datatrain 1</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('datatrain1.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Impor data dari Excel</label>
        <div class="input-group my-3">
            <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx">
            <button type="submit" class="btn btn-success">Upload Data</button>
        </div>
    </form>

    <br>

    <form action="{{ route('datatrain1.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh file beserta isinya?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete All</button>
    </form>

    <p class="mt-3"> Harap pastikan file CSV mengikuti format di bawah ini:</p>
<pre class="mt-2">Nama;Usia (bulan);BB_U;TB_U;BB_TB
Fitri;25;Kurang;Normal;Gizi Baik
Yusuf;30;Normal;Pendek;Gizi Baik
...</pre>

    <button type="submit" name="proses" class="btn btn-success">Proses Mining</button>

    <section class="table-responsive">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Usia</th>
                <th scope="col">BB/U</th>
                <th scope="col">TB/U</th>
                <th scope="col">BB/TB</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataset1 as $dt1)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dt1->Nama }}</td>
                        <td>{{ $dt1->Usia }}</td>
                        <td>{{ $dt1->berat_badan_per_usia }}</td>
                        <td>{{ $dt1->tinggi_badan_per_usia }}</td>
                        <td>{{ $dt1->berat_badan_per_tinggi_badan }}</td>           
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection
