@extends('layouts.app')

@section('title', 'Data Training 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Training 1</h2>

    @include('pages.partials.session-notification')

    <section class="bg-light rounded border border-1 p-3">
        <section class="d-flex flex-column justify-content-between mb-4">
            <section class="d-flex">
                @hasanyrole('admin|admin puskesmas')
                <a href="{{ route('datatrain1-mining') }}" class="btn btn-success">Proses Mining</a>
                @endhasanyrole
            </section>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $dt1)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt1['nama'] }}</td>
                            <td>{{ $dt1['usia'] }}</td>
                            <td>{{ $dt1['berat_badan_per_usia'] }}</td>
                            <td>{{ $dt1['tinggi_badan_per_usia'] }}</td>
                            <td>{{ $dt1['berat_badan_per_tinggi_badan'] }}</td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </section>
</section>
@endsection
