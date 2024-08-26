@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
<section class="container p-4">
  <h2 class="pb-4" style="color:#435EBE">Data Pengguna</h2>
  <section>
    @role('admin')
      <button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          Tambah Pengguna
      </button>

      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Tambah Data Pengguna</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="{{ route('user.store') }}" method="POST" autocomplete="off" aria-autocomplete="false">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi">
                  </div>
                  <div class="mt-4 mb-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
          </div>
        </div>
      </div>
    @endrole
  </section>

  <section class="table-responsive">
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">Nama</th>
              <th scope="col">Email</th>
              @hasanyrole('admin|admin puskesmas')
                <th scope="col">Opsi</th>
              @endhasanyrole
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email}}</td>
                    @hasanyrole('admin|admin puskesmas')
                      <td>
                          <section class="d-flex gap-2">
                              <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning text-white">
                                  <i class="bi bi-pencil-square"></i>
                              </a>
                              <form action="{{ route('user.destroy', $user->id) }}" method="post">
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
</section>
@endsection