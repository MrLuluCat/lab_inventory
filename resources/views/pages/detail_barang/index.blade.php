@extends('layouts.app')

@section('title', 'Daftar Detail Barang')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Daftar Detail Barang</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Barang</h6>
            <div class="mt-3">
                <a href="{{ route('detail_barang.create') }}" class="btn btn-primary">Tambah Detail Barang</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Jenis Barang</th>
                            <th>Kode Inventaris</th>
                            <th>Merek</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>No Surat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailBarang as $barang)
                            <tr data-id="{{ $barang->id }}">
                                <td class="details-control"></td>
                                <td>{{ $barang->barang->nama_barang }}</td>
                                <td>{{ $barang->kode_inventaris }}</td>
                                <td>{{ $barang->merek }}</td>
                                <td>{{ $barang->status }}</td>
                                <td>{{ $barang->ruangan->nama_ruangan }}</td>
                                <td>{{ $barang->suratMasuk->no_surat }}</td>
                                <td>
                                    <a href="{{ route('detail_barang.edit', $barang->id) }}" class="btn btn-warning">Edit</a>
                                    <form id="delete-form-{{ $barang->id }}" action="{{ route('detail_barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $barang->id }})">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        '<th>No Surat</th>' +
                        '<th>Tanggal Surat Masuk</th>' +
                        '<th>Document Path</th>' +
                    '</tr>' +
                '</thead>' +
                '<tfoot>' +
                    '<tr>' +
                        '<th>No Surat</th>' +
                        '<th>Tanggal Surat Masuk</th>' +
                        '<th>Document Path</th>' +
                    '</tr>' +
                '</tfoot>' +
            '</table>';
        }

        var table = $('#dataTable').DataTable({
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                { "data": "jenis_barang" },
                { "data": "kode_inventaris" },
                { "data": "merek" },
                { "data": "status" },
                { "data": "ruangan.nama_ruangan" },
                { "data": "suratMasuk.no_surat" },
                { "data": "aksi" }
            ],
            "order": [[1, 'asc']]
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
                        url: '/api/child-data/' + rowId,
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
                    columns: [
                        { "data": "no_surat" },
                        { "data": "tanggal_surat_masuk" },
                        { 
                            "data": "document_path",
                            "render": function(data, type, row) {
                                var fileExtension = data.split('.').pop().toLowerCase();
                                if (['png', 'jpg', 'jpeg'].includes(fileExtension)) {
                                    return '<a href="{{ asset('storage') }}/' + data + '" target="_blank">Lihat Gambar</a>';
                                } else {
                                    return '<a href="{{ asset('storage') }}/' + data + '" target="_blank">Lihat Dokumen</a>';
                                }
                            }
                        }
                    ],
                    initComplete: function () {
                        this.api().columns().every(function () {
                            var column = this;
                            var select = $('<select><option value=""></option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
