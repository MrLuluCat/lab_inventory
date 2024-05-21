@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Ruangan</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Ruangan</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ruangan.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama_ruangan">Nama Ruangan</label>
                                <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" required>
                            </div>
                            <div class="form-group">
                                <label for="status_ruangan">Status Ruangan</label>
                                <select class="form-control" id="status_ruangan" name="status_ruangan" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak aktif">Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kapasitas">Kapasitas</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
