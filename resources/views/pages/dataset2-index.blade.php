@extends('layouts.app')

@section('title', 'Dataset 2')

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data 2</h2>

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
                                <select class="form-select" id="Usia" name="Usia">
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    @for ($i = 0; $i <= 70; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                                <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    <option value="{{ ucwords('gizi baik') }}">Gizi Baik</option>
                                    <option value="{{ ucwords('gizi kurang') }}">Gizi Kurang</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Menu" class="form-label">Menu Makanan</label>
                                <select class="form-select" id="Menu" name="Menu">
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    @for ($i = 0; $i <= 18; $i++)
                                        <option value="{{ "M$i" }}">{{ "M$i" }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Keterangan" class="form-label">Keterangan</label>
                                <select class="form-select" id="Keterangan" name="Keterangan" placeholder="Silahkan Pilih">
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    <option value="{{ ucwords('baik') }}">Baik</option>
                                    <option value="{{ ucwords('tidak baik') }}">Tidak Baik</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <details class="my-3 p-2">
        <summary class="fs-5">Keterangan</summary>
        <ul>
            <li><strong>BB/TB</strong>, berat badan per tinggi badan</li>
        </ul>
    </details>

    <section class="table-responsive">
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
                                    <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#edit_{{ $dt2->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#delete_{{ $dt2->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </section>
                            </td>
                        @endhasanyrole
                    </tr>

                    {{-- Edit --}}
                    <div class="modal fade" id="edit_{{ $dt2->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit_{{ $dt2->id }}_label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="edit_{{ $dt2->id }}_label">Edit Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('dataset2.update', $dt2->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="Usia" class="form-label">Usia (bulan)</label>
                                            <select class="form-select" id="Usia" name="Usia">
                                                @for ($i = 0; $i <= 70; $i++)
                                                    <option value="{{ $i }}" {{ $dt2->Usia == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="berat_badan_per_tinggi_badan" class="form-label">BB/TB</label>
                                            <select class="form-select" id="berat_badan_per_tinggi_badan" name="berat_badan_per_tinggi_badan">
                                                <option value="{{ ucwords('gizi baik') }}" @selected(strtolower($dt2->berat_badan_per_tinggi_badan) == strtolower('gizi baik'))>Gizi Baik</option>
                                                <option value="{{ ucwords('gizi kurang') }}" @selected(strtolower($dt2->berat_badan_per_tinggi_badan) == strtolower('gizi kurang'))>Gizi Kurang</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Menu" class="form-label">Menu Makanan</label>
                                            <select class="form-select" id="Menu" name="Menu" placeholder="Silahkan Pilih">
                                                @for ($i = 0; $i <= 18; $i++)
                                                    <option value="M{{ $i }}" @selected($dt2->Menu == "M$i")>{{ "M{$i}" }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Keterangan" class="form-label">Keterangan</label>
                                            <select class="form-select" id="Keterangan" name="Keterangan" placeholder="Silahkan Pilih">
                                                <option value="{{ ucwords('baik') }}" @selected(strtolower($dt2->Keterangan) == strtolower('baik'))>Baik</option>
                                                <option value="{{ ucwords('tidak baik') }}" @selected(strtolower($dt2->Keterangan) == strtolower('tidak baik'))>Tidak Baik</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Edit --}}

                    {{-- Delete --}}
                    <div class="modal fade" id="delete_{{ $dt2->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete_{{ $dt2->id }}_label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="delete_{{ $dt2->id }}_label">Hapus Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('dataset2.destroy', $dt2->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <p>Yakin ingin menghapus data ini?</p>
                                        <details class="mt-2 mb-3 p-2 bg-light rounded border">
                                            <summary>Rincian</summary>
                                            <ul>
                                                <li>Usia: <i>{{ $dt2->Usia }}</i></li>
                                                <li>BB/TB: <i>{{ ucwords($dt2->berat_badan_per_tinggi_badan) }}</i></li>
                                                <li>Menu: <i>{{ ucwords($dt2->Menu) }}</i></li>
                                                <li>Keterangan: <i>{{ ucwords($dt2->Keterangan) }}</i></li>
                                            </ul>
                                        </details>
                                        <section class="d-flex gap-3">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </section>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Delete --}}
                @endforeach
            </tbody>
        </table>
    </section>
</section>
@endsection
