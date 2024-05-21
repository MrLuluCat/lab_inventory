@extends('layouts.app')

@section('title', 'Daftar Ruangan')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Ruangan</h2>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}"
                });
            </script>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Ruangan</h6>
                <a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-sm float-right">Tambah Ruangan</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ruangan</th>
                                <th>Status Ruangan</th>
                                <th>Kapasitas</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruangan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_ruangan }}</td>
                                    <td>{{ $item->status_ruangan }}</td>
                                    <td>{{ $item->kapasitas }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        <a href="{{ route('ruangan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">Hapus</button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('ruangan.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
