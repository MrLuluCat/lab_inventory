@extends('layouts.app')

@section('title', 'Daftar Penempatan Barang')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Penempatan Barang</h2>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Penempatan Barang</h6>
                <a href="{{ route('penempatan_barang.create') }}" class="btn btn-primary btn-sm float-right">Tambah Penempatan Barang</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Barang</th>
                                <th>Kode Iventaris</th>
                                <th>Merk Barang</th>
                                <th>Ruangan</th>
                                <th>Nomor PC</th>
                                <th>Tanggal Penempatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penempatanBarang as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->detailBarang->barang->nama_barang }}</td>
                                    <td>{{ $item->detailBarang->kode_inventaris }}</td>
                                    <td>{{ $item->detailBarang->merek }}</td>
                                    <td>{{ $item->ruangan->nama_ruangan }}</td>
                                    <td>{{ $item->pc_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_penempatan)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('penempatan_barang.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">Hapus</button>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('penempatan_barang.destroy', $item->id) }}" method="POST" class="d-inline">
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
