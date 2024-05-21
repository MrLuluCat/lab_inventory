@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Barang</h2>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
                <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm float-right">Tambah Barang</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kategori Barang</th>
                                <th>Quantity</th>
                                <th>Barang Tersedia</th>
                                <th>Barang Digunakan</th>
                                <th>Barang Tidak Digunakan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->kategori_barang }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->barang_tersedia }}</td>
                                    <td>{{ $item->barang_digunakan }}</td>
                                    <td>{{ $item->barang_tidak_digunakan }}</td>
                                    <td>
                                        <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">Hapus</button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline">
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

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection