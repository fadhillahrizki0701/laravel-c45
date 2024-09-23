@extends('layouts.app')

@section('title', 'Data Testing 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Testing 1</h2>
    
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

    @hasanyrole('admin|admin puskesmas')
        <button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Cek Klasifikasi
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
                    <form action="{{ route('datatest1.index') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama">
                        </div>
                        <div class="mb-3">
                            <label for="usia" class="form-label">Usia<span class="text-danger">*</span></label>
                            <select class="form-select" id="usia" name="usia">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('fase 1') }}">Fase 1 (0-18 bulan)</option>
                                <option value="{{ ucwords ('fase 2') }}">Fase 2 (19-36 bulan)</option>
                                <option value="{{ ucwords ('fase 3') }}">Fase 3 (37-54 bulan)</option>
                                <option value="{{ ucwords ('fase 4') }}">Fase 4 (55-72 bulan)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="berat_badan_per_usia" class="form-label" title="Berat Badan Per Usia">BB/U<span class="text-danger">*</span></label>
                            <select class="form-select" id="berat_badan_per_usia" name="berat_badan_per_usia">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('normal') }}">Normal</option>
                                <option value="{{ ucwords ('kurang') }}">Kurang</option>
                                <option value="{{ ucwords ('sangat kurang') }}">Sangat Kurang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tinggi_badan_per_usia" class="form-label" title="Tinggi Badan Per Usia">TB/U<span class="text-danger">*</span></label>
                            <select class="form-select" id="tinggi_badan_per_usia" name="tinggi_badan_per_usia">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('normal') }}">Normal</option>
                                <option value="{{ ucwords('pendek') }}">Pendek</option>
                                <option value="{{ ucwords('sangat pendek') }}">Sangat Pendek</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Cek</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (isset($predictedLabel) && isset($data))
        <section class="border border-4 rounded p-3" style="width: max-content;">
            <h4>Hasil Klasifikasi</h4>
            <section class="my-3">
                <p class="m-0">Nama: {{ $data['nama'] }}</p>
                <p class="m-0">Usia: {{ $data['usia'] }}</p>
                <p class="m-0">Berat Badan per Usia: {{ $data['berat_badan_per_usia'] }}</p>
                <p class="m-0">Tinggi Badan per Usia: {{ $data['tinggi_badan_per_usia'] }}</p>
            </section>
            <h5><u>{{ $predictedLabel }}</u></h5>
        </section>
    @endif

    <section class="my-3 mb-5">
        <form action="{{ route('datatest1.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="file">Impor data dari Excel</label>
            <div class="input-group my-3">
                <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx">
                <button type="submit" class="btn btn-success">Unggah Data</button>
            </div>
        </form>
    </section>

    @if (isset($predictedLabels))
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
                    @foreach ($predictedLabels as $predictedLabel)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $predictedLabel['nama'] }}</td>
                            <td>{{ $predictedLabel['usia'] }}</td>
                            <td>{{ $predictedLabel['berat_badan_per_usia'] }}</td>
                            <td>{{ $predictedLabel['tinggi_badan_per_usia'] }}</td>
                            <td><strong>{{ $predictedLabel['predicted_label'] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif
</section>
@endsection
