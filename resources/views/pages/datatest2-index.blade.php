@extends('layouts.app')

@section('title', 'Data Testing 2')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Testing 2</h2>
    
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

    <button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Cek Klasifikasi
    </button>
    @if (isset($predictedLabel) && isset($data))
        <button type="button" class="btn btn-info my-4" data-bs-toggle="modal" data-bs-target="#classificationResult">
            Lihat Hasil Klasifikasi
        </button>
    @endif

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Input Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('datatest2.index') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="usia" class="form-label">Usia</label>
                            <select class="form-select" id="usia" name="usia">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('fase 1') }}">Fase 1 (0-5 bulan)</option>
                                <option value="{{ ucwords ('fase 2') }}">Fase 2 (6-11 bulan)</option>
                                <option value="{{ ucwords ('fase 3') }}">Fase 3 (12-47 bulan)</option>
                                <option value="{{ ucwords ('fase 4') }}">Fase 4 (48-72 bulan)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                            <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                <option value="{{ ucwords('gizi baik') }}">Gizi Baik</option>
                                <option value="{{ ucwords('gizi kurang') }}">Gizi Kurang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="menu" class="form-label">Menu Makanan</label>
                            <select class="form-select" id="menu" name="menu" placeholder="Silahkan Pilih">
                                <option selected disabled>-- Silahkan Pilih --</option>
                                @for ($i = 1; $i <= 18; $i++)
                                    <option value="M{{ $i }}">{{ "M{$i}" }}</option>
                                @endfor
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
        <div class="modal fade" id="classificationResult" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="classificationResult" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classificationResult">Hasil Klasifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('datatest2.index') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="usia" class="form-label">Usia</label>
                                <select class="form-select" id="usia" name="usia" disabled>
                                    <option disabled>-- Silahkan Pilih --</option>
                                    <option value="{{ ucwords('fase 1') }}" @selected(strtolower($data['usia']) == 'fase 1')>Fase 1 (0-5 bulan)</option>
                                    <option value="{{ ucwords ('fase 2') }}" @selected(strtolower($data['usia']) == 'fase 2')>Fase 2 (6-11 bulan)</option>
                                    <option value="{{ ucwords ('fase 3') }}" @selected(strtolower($data['usia']) == 'fase 3')>Fase 3 (12-47 bulan)</option>
                                    <option value="{{ ucwords ('fase 4') }}" @selected(strtolower($data['usia']) == 'fase 4')>Fase 4 (48-72 bulan)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                                <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan" disabled>
                                    <option value="{{ ucwords('gizi baik') }}" @selected(strtolower($data['berat_badan_per_tinggi_badan']) == strtolower('gizi baik'))>Gizi Baik</option>
                                    <option value="{{ ucwords('gizi kurang') }}" @selected(strtolower($data['berat_badan_per_tinggi_badan']) == strtolower('gizi kurang'))>Gizi Kurang</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="menu" class="form-label">Menu Makanan</label>
                                <select class="form-select" id="menu" name="menu" placeholder="Silahkan Pilih" disabled>
                                    @for ($i = 1; $i <= 18; $i++)
                                        <option value="M{{ $i }}" @selected($data['menu'] == "M$i")>{{ "M{$i}" }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label" title="Berat Badan per Tinggi Badan">Hasil Klasifikasi (Keterangan)</label>
                                <input type="text" class="form-control" name="keterangan" id="keterangan" value="{{ $predictedLabel }}" readonly>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <hr class="my-4" />

    <section class="my-3 mb-5">
        <form action="{{ route('datatest2.store') }}" method="POST" enctype="multipart/form-data">
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
                        <th scope="col">Usia</th>
                        <th scope="col">BB/TB</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($predictedLabels as $predictedLabel)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $predictedLabel['usia'] }}</td>
                            <td>{{ $predictedLabel['menu'] }}</td>
                            <td>{{ $predictedLabel['berat_badan_per_tinggi_badan'] }}</td>
                            <td><strong>{{ $predictedLabel['predicted_label'] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif
</section>
@endsection
