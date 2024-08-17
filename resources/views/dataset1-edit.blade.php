@extends('layouts.app')
 
@section('title', 'Dataset 1')
 
@section('content')
<section class="container p-4">

    <h1>Data 1</h1>
    @if(count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div>
    @endif

<section>

    <form action="{{ route('dataset1.update', $dataset1->id) }}" method="POST">
        @csrf
        @method("put")
        <div class="mb-3">
            <label for="Nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Masukkan Nama" value="{{$dataset1->Nama }}">
        </div>
        <div class="mb-3">
            <label for="Usia" class="form-label">Usia (bulan)</label>
            <select class="form-select" id="Usia" name="Usia">
                @for ($i = 0; $i <= 70; $i++)
                    <option value="{{ $i }}" {{ $dataset1->Usia == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label for="berat_badan_per_usia" class="form-label">BB/U</label>
            <select class="form-select" id="berat_badan_per_usia" name="berat_badan_per_usia">
                <option value="normal" {{ $dataset1->berat_badan_per_usia == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="kurang" {{ $dataset1->berat_badan_per_usia == 'kurang' ? 'selected' : '' }}>Kurang</option>
                <option value="sangat kurang" {{ $dataset1->berat_badan_per_usia == 'sangat kurang' ? 'selected' : '' }}>Sangat Kurang</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="tinggi_badan_per_usia" class="form-label">TB/U</label>
            <select class="form-select" id="tinggi_badan_per_usia" name="tinggi_badan_per_usia">
                <option value="normal" {{ $dataset1->tinggi_badan_per_usia == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="pendek" {{ $dataset1->tinggi_badan_per_usia == 'pendek' ? 'selected' : '' }}>Pendek</option>
                <option value="sangat pendek" {{ $dataset1->tinggi_badan_per_usia == 'sangat pendek' ? 'selected' : '' }}>Sangat Pendek</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
            <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                <option value="gizi baik" {{ $dataset1->berat_badan_per_tinggi_badan == 'gizi baik' ? 'selected' : '' }}>Gizi Baik</option>
                <option value="gizi kurang" {{ $dataset1->berat_badan_per_tinggi_badan == 'gizi kurang' ? 'selected' : '' }}>Gizi Kurang</option>
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
</section>

</section>
@endsection
