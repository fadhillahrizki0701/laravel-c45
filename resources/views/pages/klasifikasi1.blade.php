@extends('layouts.app')

@section('title', 'Klasifikasi 1')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Dataset 1</h2>
    
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
                        <th scope="col">Hasil Klasifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataset1 as $dt1)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dt1->nama }}</td>
                            <td>{{ ucwords($dt1->usia) }}</td>
                            <td>{{ ucwords($dt1->berat_badan_per_usia)  }}</td>
                            <td>{{ ucwords($dt1->tinggi_badan_per_usia ) }}</td>
                            <td>{{ ucwords( $dt1->berat_badan_per_tinggi_badan) }}</td>
                            <td><strong>{{ $predictedLabel['predicted_label'] }}</strong></td>
                
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </section>
    </section>
</section>
@endsection
