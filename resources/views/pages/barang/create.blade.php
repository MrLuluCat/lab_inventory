@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Barang</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Barang</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('barang.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                            </div>
                            <div class="form-group">
                                <label for="kategori_barang">Kategori Barang</label>
                                {{-- <input type="text" class="form-control" id="kategori_barang" name="kategori_barang" required> --}}
                                <select class="form-control" name="kategori_barang" id="">
                                    <option value="Peripheral">Peripheral</option>
                                    <option value="PC-Parts">PC-Parts</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
