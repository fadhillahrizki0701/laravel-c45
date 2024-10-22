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
                                <td>{{ $metrices['accuracy'] }} (<span class="text-success">{{ $metrices['correct_predictions'] }}</span>/{{ $metrices['total_test_data'] }})</td>
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
                    <th scope="col">Usia</th>
                    <th scope="col">BB/TB</th>
                    <th scope="col">Menu</th>
                    <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $dt2)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt2['usia'] }}</td>
                            <td>{{ $dt2['berat_badan_per_tinggi_badan'] }}</td>
                            <td>{{ $dt2['menu'] }}</td>
                            <td>{{ $dt2['keterangan'] }}</td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>

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
                                @for ($i = 1; $i <= 4; $i++)
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
                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="M{{ $i }}" @selected($data['menu'] == "M$i")>{{ "M{$i}" }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label" title="Berat Badan per Tinggi Badan">Hasil Klasifikasi (Keterangan)</label>
                                <input type="text" class="form-control" name="keterangan" id="keterangan" value="{{ $predictedLabel ?? "Tidak Diketahui" }}" readonly>
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

    <section class="bg-light my-3 p-3">
        @if(count($rules) > 0)
            <details>
                <summary><h5 class="d-inline">Rules</h5></summary>
                <br/>
                <ul style="list-style: none; margin-left: 0;" class="bg-white p-0 p-3 rounded text-secondary">
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
</section>
@endsection
