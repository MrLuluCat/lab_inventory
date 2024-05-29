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
                                <th></th>
                                <th>No Surat</th>
                                <th>Tanggal Surat</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suratMasuk as $surat)
                                <tr data-id="{{ $surat->id }}">
                                    <td class="details-control"></td>

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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function format(id) {
                var tableId = 'child-table-' + id;
                return '<table id="' + tableId + '" class="display" style="width:100%">' +
                '<thead>' +
                    '<tr>' +
                        '<th>Jenis Barang</th>' +
                        '<th>Merek</th>' +
                        '<th>Kode Inventaris</th>' +
                        '<th>Status</th>' +
                        '<th>Lokasi</th>' +
                    '</tr>' +
                '</thead>' +
                '<tfoot>' +
                    '<tr>' +
                        '<th>Jenis Barang</th>' +
                        '<th>Merek</th>' +
                        '<th>Kode Inventaris</th>' +
                        '<th>Status</th>' +
                        '<th>Lokasi</th>' +
                    '</tr>' +
                '</tfoot>' +
            '</table>';
            }

            var table = $('#dataTable').DataTable({
                "columns": [{
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {
                        "data": "no_surat"
                    },
                    {
                        "data": "tanggal_surat_masuk"
                    },
                    {
                        "data": "document_path"
                    },
                    {
                        "data": "aksi"
                    }
                ],
                "order": [
                    [1, 'asc']
                ]
            });

            $('#dataTable tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var rowId = tr.data('id');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(format(rowId)).show();
                    tr.addClass('shown');

                    var childTableId = 'child-table-' + rowId;
                    $('#' + childTableId).DataTable({
                        ajax: {
                            url: '/api/child-barang/' + rowId,
                            dataSrc: 'data',
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Mengambil data...',
                                    text: 'Harap tunggu...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            complete: function() {
                                Swal.close();
                            }
                        },
                        columns: [{
                                "data": "barang.nama_barang"
                            },
                            {
                                "data": "merek"
                            },
                            {
                                "data": "kode_inventaris"
                            },
                            {
                                "data": "status"
                            },
                            {
                                "data": "ruangan.nama_ruangan",
                            }
                        ],
                        initComplete: function() {
                            this.api().columns().every(function() {
                                var column = this;
                                var select = $(
                                        '<select><option value=""></option></select>')
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util.escapeRegex($(
                                            this).val());
                                        column.search(val ? '^' + val + '$' : '',
                                            true, false).draw();
                                    });

                                column.data().unique().sort().each(function(d, j) {
                                    select.append('<option value="' + d + '">' +
                                        d + '</option>');
                                });
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
