@extends('layouts.app')
 
@section('title', 'Dataset 2')
 
@section('content')
<section class="container p-4">
    <span class="fs-3 " style="color:#435EBE">Data 2</span>

    @if(count($errors)>0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session()->has('success'))
    <div class="alert alert-success">
        <p>{{ session()->get('success') }}</p>
    </div>
    @endif

    <section>
        @hasanyrole('admin|admin puskesmas')
            <button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Tambah Data
            </button>
        @endhasanyrole

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Input Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('dataset2.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="Usia" class="form-label">Usia (bulan)</label>
                                <select class="form-select" id="Usia" name="Usia" placeholder="Silahkan Pilih">
                                    @for ($i = 0; $i <= 70; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                                <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan" placeholder="Silahkan Pilih">
                                    <option selected>Silahkan Pilih</option>
                                    <option value="gizi baik">Gizi Baik</option>
                                    <option value="gizi kurang">Gizi Kurang</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Menu" class="form-label">Menu Makanan</label>
                                <select class="form-select" id="Menu" name="Menu" placeholder="Silahkan Pilih">
                                    @for ($i = 0; $i <= 18; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Keterangan" class="form-label">Keterangan</label>
                                <select class="form-select" id="Keterangan" name="Keterangan" placeholder="Silahkan Pilih">
                                    <option selected>Silahkan Pilih</option>
                                    <option value="baik">Baik</option>
                                    <option value="tidak baik">Tidak Baik</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Usia</th>
                <th scope="col">BB/TB</th>
                <th scope="col">Menu</th>
                <th scope="col">Keterangan</th>
                @hasanyrole('admin|admin puskesmas')
                    <th scope="col">Opsi</th>
                @endhasanyrole
            </tr>
        </thead>
        <tbody>
            @foreach ($dataset2 as $dt2)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dt2->Usia }}</td>
                    <td>{{ ucwords($dt2->berat_badan_per_tinggi_badan) }}</td>
                    <td>{{ $dt2->Menu }}</td>
                    <td>{{ ucwords($dt2->Keterangan) }}</td>
                    
                    @hasanyrole('admin|admin puskesmas')
                        <td>
                            <section class="d-flex gap-2">
                                <a href="{{ route('dataset2.edit', $dt2->id) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('dataset2.destroy', $dt2->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger text-white">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </section>
                        </td>
                    @endhasanyrole
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
