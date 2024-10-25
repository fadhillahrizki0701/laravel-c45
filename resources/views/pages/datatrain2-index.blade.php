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
