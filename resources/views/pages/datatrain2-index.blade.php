@extends('layouts.app')

@section('title', 'Data Training 2')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Training 2</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @include('pages.partials.session-notification')

    <form action="{{ route('datatrain2.store') }}" method="POST" enctype="multipart/form-data" class="">
        @csrf
        <label for="file">Impor data dari Excel</label>
        <div class="input-group my-3">
            <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx">
            <button type="submit" class="btn btn-success">Unggah Data</button>
        </div>
    </form>

    <br>

    <form action="{{ route('datatrain2.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh file beserta isinya?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Bersihkan Data</button>
    </form>

    <details class="my-3 p-2">
        <summary class="fs-5">Keterangan</summary>
        <ul>
            <li><strong>BB/TB</strong>, berat badan per tinggi badan</li>
        </ul>
    </details>

<p class="mt-3"> Harap pastikan file CSV mengikuti format di bawah ini:</p>
<pre class="mt-2">usia (bulan);BB_TB;menu;keterangan
12;Gizi Baik;M15;Tidak Baik
18;Gizi Baik;M9;Baik
...</pre>
    
    <button type="submit" name="proses" class="btn btn-success">Proses Mining</button>

    <section class="table-responsive">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Usia</th>
                    <th scope="col">BB/TB</th>
                    <th scope="col">Menu</th>
                    <th scope="col">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataset2 as $db2)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $db2->usia }}</td>
                        <td>{{ $db2->berat_badan_per_tinggi_badan }}</td>
                        <td>{{ $db2->menu }}</td>
                        <td>{{ $db2->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection
