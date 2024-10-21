@extends('layouts.app')

@section('title', 'Data Training 2')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Training 2</h2>

    <section class="bg-light rounded border border-1 p-3">
        <section class="d-flex flex-column justify-content-between mb-4">
            <section class="d-flex">
                <a href="{{ route('datatrain2-mining') }}" class="btn btn-success">Proses Mining</a>
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
                                <td>{{ $metrices['accuracy'] }}</td>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>
</section>
@endsection
