@extends('layouts.app')
 
@section('title', 'user')
 
@section('content')
<section class="p-4 " style="width: 100%">

<span class="fs-3 " style="color:#435EBE">User</span>
<section>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Tambah User
</button>

<!-- Modal -->
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
<table id="example" class="display" style="width:100%">
    <thead>
        <tr>
        <th scope="col">No</th>
        <th scope="col">Nama</th>
        <th scope="col">Email</th>
        <th scope="col">Actions</th> 
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($users as $us)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $us->name }}</td>
                <td>{{ $us->email}}</td>
                <td>
                    <!-- Edit Button -->
                    <a href="{{ route('user.edit', $us->id) }}" class="text-decoration-none text-success">
                     Edit
                  </a>
                  
                    <!-- Delete Button -->
                    <form action="{{ route('user.destroy', $us->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-decoration-none text-danger btnDelete" onclick="return confirm('Are you sure you want to delete this item?');">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @php
                $i++;
            @endphp
        @endforeach
    </tbody>
</table>
</section>
@endsection