@extends('layouts.app')

@section('title', 'Klasifikasi Dataset 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Klasifikasi Dataset 1</h2>
    
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
        <section class="d-flex flex-column justify-content-between mb-4">
            <section class="d-flex gap-4 mb-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Cek Klasifikasi
                </button>
                @if (isset($predictedLabel) && isset($data))
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#classificationResult">
                        Lihat Hasil Klasifikasi
                    </button>
                @endif
            </section>
            <div class="py-2 mt-2">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="table-secondary">
                                <th>Accuracy</th>
                                <th>Precision</th>
                                <th>Recall</th>
                                <th>F1 Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $metrices['accuracy'] }}% (<span class="text-success">{{ $metrices['correct_predictions'] }}</span>/{{ $metrices['total_test_data'] }})</td>
                                <td>{{ $metrices['precision'] }}</td>
                                <td>{{ $metrices['recall'] }}</td>
                                <td>{{ $metrices['f1_score'] }}</td>
                            </tr>
                        </tbody>
                    </table>
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
                        <th scope="col">Prediksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metrices['predictions'] as $dt1)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt1['nama'] }}</td>
                            <td>{{ $dt1['usia'] }}</td>
                            <td>{{ $dt1['berat_badan_per_usia'] }}</td>
                            <td>{{ $dt1['tinggi_badan_per_usia'] }}</td>
                            <td>{{ $dt1['berat_badan_per_tinggi_badan'] }}</td> 
                            <td><strong>{{ $dt1['predicted_label'] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>

    <section class="bg-light my-3 p-3 border border-1">
        @if(count($rules) > 0)
            <details>
                <summary><h5 class="d-inline">Rules</h5></summary>
                <br/>
                <ul style="list-style: none; margin-left: 0;" class="bg-white p-0 p-3 rounded text-secondary border border-1 overflow-x-auto">
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
            </details>
        @endif
    </section>

    <div class="card" style="width: max-content;">
        <div class="card-body bg-light">
            <h5 class="card-title">Confusion Matrix</h5>
            <table class="table text-nowrap my-4">
                <thead>
                    <tr>
                        <th rowspan="2">Aktual</th>
                        <th colspan="2" class="text-center">Prediksi</th>
                    </tr>
                    <tr>
                        @foreach ($metrices['labels'] as $label)
                            <th>{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{ $metrices['labels'][0] }}</th>
                        <td>{{ $metrices['confusion_matrix']['TP'] }}</td>
                        <td>{{ $metrices['confusion_matrix']['TN'] }}</td>
                    </tr>
                    <tr>
                        <th>{{ $metrices['labels'][1] }}</th>
                        <td>{{ $metrices['confusion_matrix']['FP'] }}</td>
                        <td>{{ $metrices['confusion_matrix']['FN'] }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                            <td>{{ $metrices['confusion_matrix']['TP'] + $metrices['confusion_matrix']['FP'] }}</td>
                            <td>{{ $metrices['confusion_matrix']['TN'] + $metrices['confusion_matrix']['FN'] }}</td>
                    </tr>
                </tfoot>
            </table>
            <button class="btn btn-info" type="button"><i class="bi bi-info-square-fill"></i> {{ $metrices['accuracy'] }}%</button>
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
</section>
@endsection
