@extends('layouts.app')

@section('title', 'Klasifikasi Dataset 2')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Klasifikasi Dataset 2</h2>
    
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
                    <th scope="col">Usia</th>
                    <th scope="col">BB/TB</th>
                    <th scope="col">Menu</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Prediksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metrices['predictions'] as $dt2)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt2['usia'] }}</td>
                            <td>{{ $dt2['berat_badan_per_tinggi_badan'] }}</td>
                            <td>{{ $dt2['menu'] }}</td>
                            <td>{{ $dt2['keterangan'] }}</td>
                            <td><strong>{{ $dt2['predicted_label'] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>

    <section class="row gap-y-3 my-4">
        <section class="col mb-3">
            <section class="bg-light p-3 border border-1">
                @if(count($rules) > 0)
                    <details>
                        <summary class=""><h5 class="d-inline">Rules</h5></summary>
                        <br/>
                        <ul style="list-style: none; margin-left: 0;" class="bg-white p-0 p-3 rounded text-secondary border border-1  overflow-x-auto">
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
        </section>
        <section class="col mb-3">
            <div class="card" style="width: 100%;">
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
        </section>
    </section>
</section>
@endsection
