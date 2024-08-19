@extends('layouts.app')
 
@section('title', 'Datatrain 1')
 
@section('content')
<section>
    <div class="container p-4">
        <h3 class="text-center" style="color:#435EBE">Mining</h3>
    
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
        <div class="form-group my-3">
            <label for="file">Import data from excel</label>
            <input type="file" name="file" class="form-control" accept=".csv">
        </div>
        <button type="submit" name="upload" class="btn btn-success">Upload Data</button>
    </form>

    <br>

    <form action="{{ route('datatrain1.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all files and associated data?');">
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
@endsection
