@extends('layouts.app')

@section('title', 'Daftar Surat Masuk')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Surat Masuk</h2>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Surat Masuk</h6>
            </div>
            <a href="{{ route('surat_masuk.create') }}" class="btn btn-primary btn-sm float-right">Tambah Surat Masuk</a>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Surat</th>
                                <th>Tanggal Surat</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suratMasuk as $index => $surat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $surat->no_surat }}</td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat_masuk)->format('d/m/Y') }}</td>
                                    <td>
                                        @if (in_array(pathinfo($surat->document_path, PATHINFO_EXTENSION), ['png', 'jpg', 'jpeg']))
                                            <a href="{{ asset('storage/' . $surat->document_path) }}" target="_blank">Lihat
                                                Gambar</a>
                                        @else
                                            <a href="{{ asset('storage/' . $surat->document_path) }}" target="_blank">Lihat
                                                Dokumen</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('surat_masuk.edit', $surat->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $surat->id }})">Hapus</button>
                                        <form id="delete-form-{{ $surat->id }}"
                                            action="{{ route('surat_masuk.destroy', $surat->id) }}" method="POST"
                                            class="d-inline">
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

@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

@endsection
