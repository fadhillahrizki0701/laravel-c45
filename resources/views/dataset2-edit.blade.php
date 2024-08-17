@extends('layouts.app')
 
@section('title', 'Dataset 2')
 
@section('content')
<section class="container p-4">

    <h1>Data 2</h1>
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

    <form action="{{ route('dataset2.update', $dataset2->id) }}" method="POST">
        @csrf
        @method("put")
        <div class="mb-3">
            <label for="Usia" class="form-label">Usia (bulan)</label>
            <select class="form-select" id="Usia" name="Usia">
                @for ($i = 0; $i <= 70; $i++)
                    <option value="{{ $i }}" {{ $dataset2->Usia == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>  
        <div class="mb-3">
            <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
            <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                <option value="Gizi Baik" {{ $dataset2->berat_badan_per_tinggi_badan == 'gizi baik' ? 'selected' : '' }}>Gizi Baik</option>
                <option value="Gizi Kurang" {{ $dataset2->berat_badan_per_tinggi_badan == 'gizi kurang' ? 'selected' : '' }}>Gizi Kurang</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="Menu" class="form-label">Menu</label>
            <select class="form-select" id="Menu" name="Menu">
                @for ($i = 0; $i <= 70; $i++)
                <option value="{{ $i }}" {{ $dataset2->Menu == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label for="Keterangan" class="form-label">Keterangan</label>
            <select class="form-select" id="Keterangan" name="Keterangan">
                <option value="Baik" {{ $dataset2->Keterangan == 'baik' ? 'selected' : '' }}>baik</option>
                <option value="Tidak Baik" {{ $dataset2->Keterangan == 'tidak baik' ? 'selected' : '' }}>tidak baik</option>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
</section>

</section>
@endsection
