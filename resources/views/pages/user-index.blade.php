@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
<section class="container p-4">
  <h2 class="pb-4" style="color:#435EBE">Data Pengguna</h2>

  @include('pages.partials.session-notification')

  <section>
    @role('admin|admin puskesmas')
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
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi">
                  </div>
                  <div class="mb-3">
                    <label for="role" class="form-label">Peran</label>
                    <select name="role" id="role" class="form-select" aria-label="Pilih peran">
                      <option disabled>-- Silahkan Pilih --</option>
                      @role('admin')
                        @foreach ($roles as $role)
                          <option value="{{ $role->name }}" @selected(strtolower($role->name) == strtolower('wali')) @disabled(strtolower($role->name) == strtolower('admin'))>{{ strtolower($role->name) == strtolower('wali') ? ucwords('Wali')." (default)" : ucwords($role->name) }}</option>
                        @endforeach
                      @endrole
                      @role('admin puskesmas')
                        @foreach ($roles as $role)
                          <option value="{{ $role->name }}" @selected(strtolower($role->name) == strtolower('wali')) @disabled(strtolower($role->name) == strtolower('admin') || strtolower($role->name) == strtolower('admin puskesmas'))>{{ strtolower($role->name) == strtolower('wali') ? ucwords('Wali')." (default)" : ucwords($role->name) }}</option>
                        @endforeach
                      @endrole
                    </select>
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
              <th scope="col">Peran</th>
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
                    <td>{{ $user->email }}</td>
                    <td>{{ ucwords($user->roles[0]->name) }}</td>
                    @hasanyrole('admin|admin puskesmas')
                      <td>
                          <section class="d-flex gap-2">
                              <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning text-white">
                                  <i class="bi bi-pencil-square"></i>
                              </a>
                              <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#delete_{{ $user->id }}">
                                  <i class="bi bi-trash"></i>
                              </button>
                          </section>
                      </td>
                  @endhasanyrole
                </tr>

                {{-- Delete --}}
                <div class="modal fade" id="delete_{{ $user->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete_{{ $user->id }}_label" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="delete_{{ $user->id }}_label">Hapus Data</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <form action="{{ route('user.destroy', $user->id) }}" method="post">
                                  @csrf
                                  @method('DELETE')
                                  <p>Yakin ingin menghapus data ini?</p>
                                  <details class="mt-2 mb-3 p-2 bg-light rounded border">
                                      <summary>Rincian</summary>
                                      <ul>
                                          <li>nama: <i>{{ $user->name }}</i></li>
                                          <li>Email: <i>{{ $user->email }}</i></li>
                                          <li>Peran: <i>{{ ucwords($user->roles[0]->name) }}</i></li>
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