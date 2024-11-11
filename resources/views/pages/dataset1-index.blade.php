@extends('layouts.app')

@section('title', 'Dataset 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Dataset 1</h2>
    
    @if(count($errors)>0)
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
<pre class="mt-2">nama;usia;BB_U;TB_U;BB_TB
Fitri;Fase 2;Kurang;Normal;Gizi Baik
Yusuf;Fase 2;Normal;Pendek;Gizi Baik
...</pre>
    </section>

    <details class="my-3 p-2">
        <summary class="fs-5">Keterangan</summary>
        <ul>
            <li><strong>BB/U</strong>, berat badan per usia</li>
            <li><strong>TB/U</strong>, tinggi badan per usia</li>
            <li><strong>BB/TB</strong>, berat badan per tinggi badan</li>
        </ul>
    </details>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Input Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('dataset1.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama">
                        </div>
                        <div class="mb-3">
                            <label for="usia" class="form-label">Usia (bulan)</label>
                            <select class="form-select" id="usia" name="usia">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('fase 1') }}">Fase 1 (0-5 bulan)</option>
                                <option value="{{ ucwords ('fase 2') }}">Fase 2 (6-11 bulan)</option>
                                <option value="{{ ucwords ('fase 3') }}">Fase 3 (12-47 bulan)</option>
                                <option value="{{ ucwords ('fase 4') }}">Fase 4 (48-72 bulan)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="berat_badan_per_usia" class="form-label">BB/U</label>
                            <select class="form-select" id="berat_badan_per_usia" name="berat_badan_per_usia">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('normal') }}">Normal</option>
                                <option value="{{ ucwords ('kurang') }}">Kurang</option>
                                <option value="{{ ucwords ('sangat kurang') }}">Sangat Kurang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tinggi_badan_per_usia" class="form-label">TB/U</label>
                            <select class="form-select" id="tinggi_badan_per_usia" name="tinggi_badan_per_usia">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('normal') }}">Normal</option>
                                <option value="{{ ucwords('pendek') }}">Pendek</option>
                                <option value="{{ ucwords('sangat pendek') }}">Sangat Pendek</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="berat_badan_per_tinggi_baadan" class="form-label">BB/TB</label>
                            <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('gizi baik') }}">Gizi Baik</option>
                                <option value="{{ ucwords('gizi kurang') }}">Gizi Kurang</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-light rounded border border-1 rounded-3 p-1">
        <section class="d-flex flex-column flex-lg-row justify-content-between align-items-start pb-3">
            <section class="d-flex justify-content-center align-items-end">
                <form action="{{ route('dataset-file-upload-1.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="file">Impor data dari <code>.xlsx, .csv</code></label>
                    <div class="input-group">
                        <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-upload"></i> Impor
                        </button>
                    </div>
                </form>
                @hasanyrole('admin|admin puskesmas')
                <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bi bi-plus"></i> Tambah Data
                </button>
                @endhasanyrole
            </section>
            <section class="d-flex flex-column my-3 my-lg-0 align-items-start">
                <div>
                    <p class="m-0 p-0">Opsi</p>
                </div>
                <div class="btn-group">
                    @role('admin')
                        <form action="{{ route('dataset1.split') }}" method="POST" onsubmit="return confirm('Split Dataset?');" class="d-inline">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text rounded-0 rounded-start" id="split-ratio">Split Ratio</span>
                                <input type="number" step="0.01" min="0.1" max="0.9" value="0.7" class="form-control rounded-0" placeholder="Split Ratio" aria-label="Split Ratio" aria-describedby="split-ratio" name="split_ratio">
                                <button type="submit" class="btn btn-primary rounded-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Split Dataset menjadi Data Train dan Data Test!">
                                    <i class="bi bi-database-fill-gear"></i> Split
                                </button>
                            </div>
                        </form>
                        <form action="{{ route('dataset-file-upload-1.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh file beserta isinya?');" class="d-inline">
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
                        <th scope="col">Nama</th>
                        <th scope="col">Usia</th>
                        <th scope="col">BB/U</th>
                        <th scope="col">TB/U</th>
                        <th scope="col">BB/TB</th>
                        @hasanyrole('admin|admin puskesmas')
                            <th scope="col">Opsi</th>
                        @endhasanyrole
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataset1 as $dt1)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt1->nama }}</td>
                            <td>{{ ucwords($dt1->usia) }}</td>
                            <td>{{ ucwords($dt1->berat_badan_per_usia)  }}</td>
                            <td>{{ ucwords($dt1->tinggi_badan_per_usia ) }}</td>
                            <td>{{ ucwords( $dt1->berat_badan_per_tinggi_badan) }}</td>
                            @hasanyrole('admin|admin puskesmas')
                                <td>
                                    <section class="d-flex gap-2">
                                        <a href="{{ route('dataset1.edit', $dt1->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#delete_{{ $dt1->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </section>
                                </td>
                            @endhasanyrole
                        </tr>

                        {{-- Delete --}}
                        <div class="modal fade" id="delete_{{ $dt1->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete_{{ $dt1->id }}_label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="delete_{{ $dt1->id }}_label">Hapus Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('dataset1.destroy', $dt1->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <p>Yakin ingin menghapus data ini?</p>
                                            <details class="mt-2 mb-3 p-2 bg-light rounded border">
                                                <summary>Rincian</summary>
                                                <ul>
                                                    <li>nama: <i>{{ $dt1->nama }}</i></li>
                                                    <li>usia: <i>{{ ucwords($dt1->usia) }}</i></li>
                                                    <li>BB/U: <i>{{ ucwords($dt1->berat_badan_per_usia) }}</i></li>
                                                    <li>TB/U: <i>{{ ucwords($dt1->tinggi_badan_per_usia) }}</i></li>
                                                    <li>BB/TB: <i>{{ ucwords($dt1->berat_badan_per_tinggi_badan) }}</i></li>
                                                </ul>
                                            </details>
                                            <section class="d-flex gap-3">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </section>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- End Delete --}}
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>
</section>
@endsection
