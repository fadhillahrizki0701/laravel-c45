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


</section>
@endsection
