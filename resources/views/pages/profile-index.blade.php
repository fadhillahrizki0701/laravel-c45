@extends('layouts.app')

@section('title', 'Profil '.$profile->name)

@section('content')
<section class="container p-4">
    <h2 class="pb-4" style="color:#435EBE">Profil | {{ $profile->name }}</h2>

    @include('pages.partials.session-notification')

    <form action="{{ route('profile.update', $profile->id) }}" method="POST" autocomplete="off" aria-autocomplete="false">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" value="{{ $profile->name }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" value="{{ $profile->email }}">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Peran</label>
            <select name="role" id="role" class="form-select" aria-label="Pilih peran">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(strtolower($role->name) == strtolower($profile->roles[0]->name)) disabled>{{ strtolower($role->name) == strtolower($profile->roles[0]->name) ? ucwords($role->name)." ⭐" : ucwords($role->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4 mb-2">
            {{-- <a href="{{ route('profile.index', $profile->id) }}" class="btn btn-secondary">Kembali</a> --}}
            <button type="submit" class="btn btn-warning">Simpan</button>
        </div>
    </form>
</section>
@endsection
