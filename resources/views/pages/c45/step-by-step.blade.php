@extends('layouts.app')

@section('title', 'Tabel Kalkulasi')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Tabel Kalkulasi</h2>

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
                    <th scope="col">Atribute</th>
                    <th scope="col">Gain</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gains['gains'] as $attribute => $gain)
                    <tr>
                        <td>{{ $attribute }}</td>
                        <td>{{ $gain }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection
