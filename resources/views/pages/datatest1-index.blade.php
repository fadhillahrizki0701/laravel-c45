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
                                <option value="{{ ucwords('fase 1') }}">Fase 1 (0-5 bulan)</option>
                                <option value="{{ ucwords ('fase 2') }}">Fase 2 (6-11 bulan)</option>
                                <option value="{{ ucwords ('fase 3') }}">Fase 3 (12-47 bulan)</option>
                                <option value="{{ ucwords ('fase 4') }}">Fase 4 (48-72 bulan)</option>
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
        <div class="modal fade" id="classificationResult" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="classificationResult" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classificationResult">Hasil Klasifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('datatest1.index') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" value="{{ $data['nama'] }}" readonly>
                            </div>
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
                                <label for="berat_badan_per_usia" class="form-label" title="Berat Badan Per Usia">BB/U</label>
                                <select class="form-select" id="berat_badan_per_usia" name="berat_badan_per_usia" disabled>
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    <option value="{{ ucwords('normal') }}" @selected(strtolower($data['berat_badan_per_usia']) == 'normal')>Normal</option>
                                    <option value="{{ ucwords ('kurang') }}" @selected(strtolower($data['berat_badan_per_usia']) == 'kurang')>Kurang</option>
                                    <option value="{{ ucwords ('sangat kurang') }}" @selected(strtolower($data['berat_badan_per_usia']) == 'sangat kurang')>Sangat Kurang</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tinggi_badan_per_usia" class="form-label" title="Tinggi Badan Per Usia">TB/U</label>
                                <select class="form-select" id="tinggi_badan_per_usia" name="tinggi_badan_per_usia" disabled>
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    <option value="{{ ucwords('normal') }}" @selected(strtolower($data['tinggi_badan_per_usia']) == 'normal')>Normal</option>
                                    <option value="{{ ucwords('pendek') }}" @selected(strtolower($data['tinggi_badan_per_usia']) == 'pendek')>Pendek</option>
                                    <option value="{{ ucwords('sangat pendek') }}" @selected(strtolower($data['tinggi_badan_per_usia']) == 'sangat pendek')>Sangat Pendek</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="berat_badan_per_tinggi_badan" class="form-label" title="Berat Badan per Tinggi Badan">Hasil Klasifikasi (BB/TB)</label>
                                <input type="text" class="form-control" name="berat_badan_per_tinggi_badan" id="berat_badan_per_tinggi_badan" value="{{ $predictedLabel }}" readonly>
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

        @if(count($accuracy) > 0)
        <section class="bg-light my-4 d-flex flex-column gap-1 border border-2 rounded p-4 fs-5">
            <details>
                <summary><h5 class="d-inline">Hasil</h5></summary>
                <br/>
                <div class="m-0">
                    <p class="m-0 p-0">Akurasi : {{ $accuracy['accuracy'] }}% <span class="text-secondary">(<span class="text-success">{{ $accuracy['correct_predictions'] }}</span>/{{ $accuracy['total_test_data'] }})</span></p>
                </div>
                <div class="m-0">
                    <p class="m-0 p-0">Prediksi Benar : {{ $accuracy['correct_predictions'] }} data</p>
                </div>
                <div class="m-0">
                    <p class="m-0 p-0">Total Data Uji : {{ $accuracy['total_test_data'] }} data</p>
                </div>
                <hr>
            </details>
            @if(count($rules) > 0)
                <details>
                    <summary><h5 class="d-inline">Rules</h5></summary>
                    <br/>
                    <ul style="list-style: none; margin-left: 0; padding-left: 0;">
                        @foreach ($rules as $rule)
                            @php
                                // Split the rule into lines
                                $lines = explode("\n", trim($rule));
                                $indentLevel = 0;  // Track indentation for nested IF statements
                            @endphp
                            <li>
                                @foreach ($lines as $line)
                                    @if (strpos($line, 'IF') !== false)
                                        @php
                                            // Increase the indentation for nested IFs
                                            $indentLevel++;
                                            // Add indentation spaces based on the nesting level
                                            $indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $indentLevel - 1);
                                        @endphp
                                        {!! $indentation . str_replace('IF', '├', $line) !!}<br>
                                    @elseif (strpos($line, 'THEN') !== false)
                                        @php
                                            // Reset the indentation level for THEN
                                            $indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $indentLevel);
                                        @endphp
                                        {!! $indentation . str_replace('THEN', '┗╸', $line) !!}<br>
                                        @php $indentLevel = 0; @endphp  {{-- Reset for next rule set --}}
                                    @endif
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                </details>
            @endif
        </section>
        @endif
    @endif
</section>
@endsection
