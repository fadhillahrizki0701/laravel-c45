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

    @include('pages.partials.session-notification')

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
                                <label for="usia" class="form-label">Usia (bulan)</label>
                                <select class="form-select" id="usia" name="usia">
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    <option value="{{ ucwords('fase 1') }}">Fase 1</option>
                                    <option value="{{ ucwords ('fase 2') }}">Fase 2</option>
                                    <option value="{{ ucwords ('fase 3') }}">Fase 3</option>
                                    <option value="{{ ucwords ('fase 4') }}">Fase 4</option>
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
                                <label for="menu" class="form-label">Menu Makanan</label>
                                <select class="form-select" id="menu" name="menu">
                                    <option selected disabled>-- Silahkan Pilih --</option>
                                    @for ($i = 1; $i <= 18; $i++)
                                        <option value="{{ "M$i" }}">{{ "M$i" }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <select class="form-select" id="keterangan" name="keterangan" placeholder="Silahkan Pilih">
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
                        <td>{{ $dt2->usia }}</td>
                        <td>{{ ucwords($dt2->berat_badan_per_tinggi_badan) }}</td>
                        <td>{{ $dt2->menu }}</td>
                        <td>{{ ucwords($dt2->keterangan) }}</td>
                        @hasanyrole('admin|admin puskesmas')
                            <td>
                                <section class="d-flex gap-2">
                                    <a href="{{ route('dataset2.edit', $dt2->id) }}" class="btn btn-sm btn-warning text-white">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#delete_{{ $dt2->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </section>
                            </td>
                        @endhasanyrole
                    </tr>

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
                                                <li>usia: <i>{{ $dt2->usia }}</i></li>
                                                <li>BB/TB: <i>{{ ucwords($dt2->berat_badan_per_tinggi_badan) }}</i></li>
                                                <li>menu: <i>{{ ucwords($dt2->menu) }}</i></li>
                                                <li>keterangan: <i>{{ ucwords($dt2->keterangan) }}</i></li>
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
