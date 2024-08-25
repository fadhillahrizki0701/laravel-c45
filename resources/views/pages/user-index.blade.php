@extends('layouts.app')

@section('title', 'user')

@section('content')
<section class="container p-4">
  <h2 style="color:#435EBE">Pengguna</h2>
  <section>
    <button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Tambah Pengguna
    </button>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Input Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="Nama" placeholder="Masukkan Nama">
                      </div>
                      <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="Nama" placeholder="Masukkan Nama">
                      </div>
                  </form>
                </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
    </div>
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