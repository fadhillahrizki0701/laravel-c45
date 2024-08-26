@extends('layouts.app')

@section('title', 'Dataset 1 Edit')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Dataset 1 Edit</h2>

    @include('pages.partials.session-notification')

    <section>
        <form action="{{ route('dataset1.update', $dataset1->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="Nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="Nama" name="Nama" placeholder="Masukkan Nama" value="{{ $dataset1->Nama }}">
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
                    <option value="{{ ucwords('normal') }}"  @selected(strtolower($dataset1->berat_badan_per_usia) == strtolower('normal'))>Normal</option>
                    <option value="{{ ucwords('kurang') }}"  @selected(strtolower($dataset1->berat_badan_per_usia) == strtolower('kurang'))>Kurang</option>
                    <option value="{{ ucwords('sangat kurang') }}"  @selected(strtolower($dataset1->berat_badan_per_usia) == strtolower('sangat kurang'))>Sangat Kurang</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tinggi_badan_per_usia" class="form-label">TB/U</label>
                <select class="form-select" id="tinggi_badan_per_usia" name="tinggi_badan_per_usia">
                    <option value="{{ ucwords('normal') }}" @selected(strtolower($dataset1->tinggi_badan_per_usia) == strtolower('normal'))>Normal</option>
                    <option value="{{ ucwords('pendek') }}" @selected(strtolower($dataset1->tinggi_badan_per_usia) == strtolower('pendek'))>Pendek</option>
                    <option value="{{ ucwords('sangat pendek') }}" @selected(strtolower($dataset1->tinggi_badan_per_usia) == strtolower('sangat pendek'))>Sangat Pendek</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                    <option value="{{ ucwords('gizi baik') }}" @selected(strtolower($dataset1->berat_badan_per_tinggi_badan) == strtolower('gizi baik'))>Gizi Baik</option>
                    <option value="{{ ucwords('gizi kurang') }}" @selected(strtolower($dataset1->berat_badan_per_tinggi_badan) == strtolower('gizi kurang'))>Gizi Kurang</option>
                </select>
            </div>
            <div class="modal-footer">
                <a href="{{ route('dataset1.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning">Simpan</button>
            </div>
        </form>
    </section>
</section>
@endsection