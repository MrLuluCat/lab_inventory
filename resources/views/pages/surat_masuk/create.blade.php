@extends('layouts.app')

@section('title', 'Tambah Surat Masuk')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Surat Masuk</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Surat Masuk</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('surat_masuk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="no_surat">No Surat</label>
                                <input type="text" class="form-control" id="no_surat" name="no_surat" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_surat_masuk">Tanggal Surat Masuk</label>
                                <input type="date" class="form-control" id="tanggal_surat_masuk"
                                    name="tanggal_surat_masuk" required>
                            </div>
                            <div class="form-group">
                                <label for="document_path">Upload Dokumen (PNG, JPG, PDF)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="document_path" name="document_path"
                                        accept=".png,.jpg,.jpeg,.pdf" required>
                                    <label class="custom-file-label" for="document_path">Pilih file</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add the following script to update the label of the file input with the file name
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var fileName = document.getElementById("document_path").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endsection
