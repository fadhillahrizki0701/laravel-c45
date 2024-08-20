@extends('layouts.app')
 
@section('title', 'Dataset 1')
 
@section('content')
<section class="container p-4">
    <span class="fs-3 " style="color:#435EBE">Data 1</span>
    
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
                                    @for ($i = 0; $i <= 70; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="berat_badan_per_usia" class="form-label">BB/U</label>
                                <select class="form-select" id="berat_badan_per_usia" name="berat_badan_per_usia">
                                    <option selected>Silahkan Pilih</option>
                                    <option value="{{ ucwords('normal') }}">Normal</option>
                                    <option value="{{ ucwords ('kurang') }}">Kurang</option>
                                    <option value="{{ ucwords ('sangat kurang') }}">Sangat Kurang</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tinggi_badan_per_usia" class="form-label">TB/U</label>
                                <select class="form-select" id="tinggi_badan_per_usia" name="tinggi_badan_per_usia">
                                    <option selected>Silahkan Pilih</option>
                                    <option value="{{ ucwords('normal') }}">Normal</option>
                                    <option value="{{ ucwords('pendek') }}">Pendek</option>
                                    <option value="{{ ucwords('sangat pendek') }}">Sangat Pendek</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="berat_badan_per_tinggi_baadan" class="form-label">BB/TB</label>
                                <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                                    <option selected>Silahkan Pilih</option>
                                    <option value="{{ ucwords('gizi baik') }}">Gizi Baik</option>
                                    <option value="{{ ucwords('gizi kurang') }}">Gizi Kurang</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                        <th scope="col">Opsi</th> <!-- New Actions column -->
                    @endhasanyrole
                </tr>
            </thead>
            <tbody>
                @foreach ($dataset1 as $dt1)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dt1->Nama }}</td>
                        <td>{{ $dt1->Usia }}</td>
                        <td>{{ucwords($dt1->berat_badan_per_usia)  }}</td>
                        <td>{{ucwords($dt1->tinggi_badan_per_usia ) }}</td>
                        <td>{{ucwords( $dt1->berat_badan_per_tinggi_badan )}}</td>
                        
                        @hasanyrole('admin|admin puskesmas')
                            <td>
                                <section class="d-flex gap-2">
                                    <a href="{{ route('dataset1.edit', $dt1->id) }}" class="btn btn-sm btn-warning text-white">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('dataset1.destroy', $dt1->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </section>
                            </td>
                        @endhasanyrole
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection
