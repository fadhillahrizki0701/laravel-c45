@extends('layouts.app')

@section('title', 'Edit Data Pengguna |'.$user->name)

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Data Pengguna | {{ ucwords($user->name) }}</h2>

    @include('pages.partials.session-notification')

    <section>
        @role('admin|admin puskesmas')
            <form action="{{ route('user.update', $user->id) }}" method="POST" autocomplete="off" aria-autocomplete="false">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" value="{{ $user->name }}">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" value="{{ $user->email }}">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Peran</label>
                    <select name="role" id="role" class="form-select" aria-label="Pilih peran">
                        <option disabled>-- Silahkan Pilih --</option>
                        @role('admin')
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(strtolower($role->name) == strtolower($user->roles[0]->name)) @disabled(strtolower($role->name) == strtolower('admin'))>{{ strtolower($role->name) == strtolower($user->roles[0]->name) ? ucwords($role->name)." ⭐" : ucwords($role->name) }}</option>
                            @endforeach
                        @endrole
                        @role('admin puskesmas')
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(strtolower($role->name) == strtolower($user->roles[0]->name)) @disabled(strtolower($role->name) == strtolower('admin') || strtolower($role->name) == strtolower('admin puskesmas'))>{{ strtolower($role->name) == strtolower($user->roles[0]->name) ? ucwords($role->name)." ⭐" : ucwords($role->name) }}</option>
                            @endforeach
                        @endrole
                    </select>
                </div>
                <div class="mt-4 mb-2">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-warning">Simpan</button>
                </div>
            </form>
        @endrole
    </section>
</section>
@endsection
