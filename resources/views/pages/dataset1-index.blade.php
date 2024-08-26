@extends('layouts.app')

@section('title', 'Dataset 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data 1</h2>
    
    @if(count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('success'))
        <div class="alert alert-success">
            <p>{{ session()->get('success') }}</p>
        </div>
    @endif

    <section>
        @hasanyrole('admin|admin puskesmas')
            <button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Tambah Data
            </button>
        @endhasanyrole

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
                                <label for="Nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Masukkan Nama">
                            </div>
                            <div class="mb-3">
                                <label for="Usia" class="form-label">Usia (bulan)</label>
                                <select class="form-select" id="Usia" name="Usia">
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    @for ($i = 0; $i <= 70; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
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
    </section>

    <details class="my-3 p-2">
        <summary class="fs-5">Keterangan</summary>
        <ul>
            <li><strong>BB/U</strong>, berat badan per usia</li>
            <li><strong>TB/U</strong>, tinggi badan per usia</li>
            <li><strong>BB/TB</strong>, berat badan per tinggi badan</li>
        </ul>
    </details>

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
                        <td>{{ $dt1->Nama }}</td>
                        <td>{{ $dt1->Usia }}</td>
                        <td>{{ ucwords($dt1->berat_badan_per_usia)  }}</td>
                        <td>{{ ucwords($dt1->tinggi_badan_per_usia ) }}</td>
                        <td>{{ ucwords( $dt1->berat_badan_per_tinggi_badan) }}</td>
                        @hasanyrole('admin|admin puskesmas')
                            <td>
                                <section class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#edit_{{ $dt1->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#delete_{{ $dt1->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </section>
                            </td>
                        @endhasanyrole
                    </tr>

                    {{-- Edit --}}
                    <div class="modal fade" id="edit_{{ $dt1->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit_{{ $dt1->id }}_label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="edit_{{ $dt1->id }}_label">Edit Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('dataset1.update', $dt1->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="Nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Masukkan Nama" value="{{ $dt1->Nama }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="Usia" class="form-label">Usia (bulan)</label>
                                            <select class="form-select" id="Usia" name="Usia">
                                                @for ($i = 0; $i <= 70; $i++)
                                                    <option value="{{ $i }}" {{ $dt1->Usia == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="berat_badan_per_usia" class="form-label">BB/U</label>
                                            <select class="form-select" id="berat_badan_per_usia" name="berat_badan_per_usia">
                                                <option value="{{ ucwords('normal') }}"  @selected(strtolower($dt1->berat_badan_per_usia) == strtolower('normal'))>Normal</option>
                                                <option value="{{ ucwords('kurang') }}"  @selected(strtolower($dt1->berat_badan_per_usia) == strtolower('kurang'))>Kurang</option>
                                                <option value="{{ ucwords('sangat kurang') }}"  @selected(strtolower($dt1->berat_badan_per_usia) == strtolower('sangat kurang'))>Sangat Kurang</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tinggi_badan_per_usia" class="form-label">TB/U</label>
                                            <select class="form-select" id="tinggi_badan_per_usia" name="tinggi_badan_per_usia">
                                                <option value="{{ ucwords('normal') }}" @selected(strtolower($dt1->tinggi_badan_per_usia) == strtolower('normal'))>Normal</option>
                                                <option value="{{ ucwords('pendek') }}" @selected(strtolower($dt1->tinggi_badan_per_usia) == strtolower('pendek'))>Pendek</option>
                                                <option value="{{ ucwords('sangat pendek') }}" @selected(strtolower($dt1->tinggi_badan_per_usia) == strtolower('sangat pendek'))>Sangat Pendek</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                                            <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                                                <option value="{{ ucwords('gizi baik') }}" @selected(strtolower($dt1->berat_badan_per_tinggi_badan) == strtolower('gizi baik'))>Gizi Baik</option>
                                                <option value="{{ ucwords('gizi kurang') }}" @selected(strtolower($dt1->berat_badan_per_tinggi_badan) == strtolower('gizi kurang'))>Gizi Kurang</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Edit --}}

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
                                                <li>Nama: <i>{{ $dt1->Nama }}</i></li>
                                                <li>Usia: <i>{{ $dt1->Usia }}</i></li>
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
@endsection
