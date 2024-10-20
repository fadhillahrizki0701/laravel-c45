@extends('layouts.app')

@section('title', 'Datasetset 2 Edit')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Datasetset 2 Edit</h2>

    @include('pages.partials.session-notification')

    <section>
        <form action="{{ route('dataset2.update', $dataset2->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="usia" class="form-label">Usia</label>
                <select class="form-select" id="usia" name="usia">
                    <option value="{{ ucwords('fase 1') }}"@selected(strtolower($dataset2->usia) == strtolower('fase 1'))>Fase 1</option>
                    <option value="{{ ucwords ('fase 2') }}"@selected(strtolower($dataset2->usia) == strtolower('fase 2'))>Fase 2</option>
                    <option value="{{ ucwords ('fase 3') }}"@selected(strtolower($dataset2->usia) == strtolower('fase 3'))>Fase 3</option>
                    <option value="{{ ucwords ('fase 4') }}"@selected(strtolower($dataset2->usia) == strtolower('fase 4'))>Fase 4</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                    <option value="{{ ucwords('gizi baik') }}" @selected(strtolower($dataset2->berat_badan_per_tinggi_badan) == strtolower('gizi baik'))>Gizi Baik</option>
                    <option value="{{ ucwords('gizi kurang') }}" @selected(strtolower($dataset2->berat_badan_per_tinggi_badan) == strtolower('gizi kurang'))>Gizi Kurang</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="menu" class="form-label">Menu Makanan</label>
                <select class="form-select" id="menu" name="menu" placeholder="Silahkan Pilih">
                    @for ($i = 1; $i <= 4; $i++)
                        <option value="M{{ $i }}" @selected($dataset2->menu == "M$i")>{{ "M{$i}" }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <select class="form-select" id="keterangan" name="keterangan" placeholder="Silahkan Pilih">
                    <option value="{{ ucwords('baik') }}" @selected(strtolower($dataset2->keterangan) == strtolower('baik'))>Baik</option>
                    <option value="{{ ucwords('tidak baik') }}" @selected(strtolower($dataset2->keterangan) == strtolower('tidak baik'))>Tidak Baik</option>
                </select>
            </div>
            <div class="modal-footer">
                <a href="{{ route('dataset2.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning">Simpan</button>
            </div>
        </form>
    </section>
</section>
@endsection