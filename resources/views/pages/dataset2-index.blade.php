@extends('layouts.app')

@section('title', 'Dataset 2')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Dataset 2</h2>

    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('pages.partials.session-notification')

    <section class="bg-light rounded border border-1 p-3">
    <p class="mt-3">Ketika mengunggah file <code>.xlsx</code> atau <code>.csv</code>, harap pastikan file CSV mengikuti format di bawah ini:</p>
<pre class="mt-2">usia (bulan);BB_TB;Menu;Keterangan
Fase 1;Gizi Kurang;M3;Tidak Baik
Fase 2;Gizi Baik;M2;Baik
...</pre>
    </section>

    <details class="my-3 p-2">
        <summary class="fs-5">Keterangan</summary>
        <ul>
            <li><strong>BB/TB</strong>, berat badan per tinggi badan</li>
        </ul>
    </details>

    <section class="bg-light rounded border border-1 rounded-3 p-1">
        <section class="d-flex flex-column flex-lg-row justify-content-between align-items-start pb-3">
            <form action="{{ route('dataset-file-upload-2.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="file">Impor data dari <code>.xlsx, .csv</code></label>
                <div class="input-group">
                    <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload"></i> Impor
                    </button>
                </div>
            </form>
            <section class="d-flex flex-column my-3 my-lg-0 align-items-start">
                <div>
                    <p class="m-0 p-0">Opsi</p>
                </div>
                <div class="btn-group">
                    @role('admin')
                    <form action="{{ route('dataset2.split') }}" method="POST" onsubmit="return confirm('Split Dataset?');" class="d-inline">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text rounded-0 rounded-start" id="split-ratio">Split Ratio</span>
                            <input type="number" step="0.01" min="0.1" max="0.9" class="form-control rounded-0" placeholder="Split Ratio" aria-label="Split Ratio" aria-describedby="split-ratio" name="split_ratio">
                            <button type="submit" class="btn btn-primary rounded-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Split Dataset menjadi Data Train dan Data Test!">
                                <i class="bi bi-database-fill-gear"></i> Split
                            </button>
                        </div>
                    </form>
                    <form action="{{ route('dataset-file-upload-2.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh file beserta isinya?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-0 rounded-end" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Hapus seluruh file yang telah diinput beserta data yang telah dimasukkan ke dalam database">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                    </form>
                    @endrole
                </div>
            </section>        
        </section>
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
                    @foreach ($dataset2 as $dt2)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt2->usia }}</td>
                            <td>{{ ucwords($dt2->berat_badan_per_tinggi_badan) }}</td>
                            <td>{{ $dt2->menu }}</td>
                            <td>{{ ucwords($dt2->keterangan) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>
</section>
@endsection

