@extends('layouts.app')

@section('title', 'Daftar Transaksi Pemindahan')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Transaksi Pemindahan Barang</h2>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Transaksi Pemindahan</h6>
                <a href="{{ route('penempatan_barang.create') }}" class="btn btn-primary btn-sm">Tambah Penempatan Barang</a>
            </div>


            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Ruangan Asal</th>
                                <th>Ruangan Tujuan</th>
                                <th>Tanggal Pemindahan</th>
                                <th>Jumlah Item</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksiPemindahan as $index => $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="details-control"></td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->ruanganAsal->nama_ruangan }}</td>
                                    <td>{{ $item->ruanganTujuan->nama_ruangan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pemindahan)->format('d/m/Y') }}</td>
                                    <td>{{ $item->jumlah_item }}</td>
                                    <td>
                                        <a href="{{ route('penempatan_barang.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $item->id }})">Hapus</button>
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('penempatan_barang.destroy', $item->id) }}" method="POST"
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
            // Format tampilan child table untuk menampilkan detail barang
            function format(id) {
                var tableId = 'child-table-' + id;
                return '<table id="' + tableId + '" class="display" style="width:100%">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Jenis Barang</th>' +
                    '<th>Merek</th>' +
                    '<th>PC Number</th>' +
                    '<th>Status</th>' +
                    '<th>Kode Inventaris</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tfoot>' +
                    '<tr>' +
                    '<th>Jenis Barang</th>' +
                    '<th>Merek</th>' +
                    '<th>PC Number</th>' +
                    '<th>Status</th>' +
                    '<th>Kode Inventaris</th>' +
                    '</tr>' +
                    '</tfoot>' +
                    '</table>';
            }

            // Inisialisasi DataTable untuk tabel utama
            var table = $('#dataTable').DataTable();

            // Event listener untuk menampilkan child row
            $('#dataTable tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var rowId = tr.data('id'); // ID transaksi pemindahan

                if (row.child.isShown()) {
                    // Tutup child row jika sudah terbuka
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Buka child row jika tertutup dan ambil data detail barang berdasarkan ID transaksi pemindahan
                    row.child(format(rowId)).show();
                    tr.addClass('shown');

                    // Inisialisasi DataTable untuk child row
                    var childTableId = 'child-table-' + rowId;
                    $('#' + childTableId).DataTable({
                        ajax: {
                            url: '/api/child-penempatan/' +
                                rowId, // API endpoint untuk mengambil data penempatan barang
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
                                "data": "detail_barang.barang.nama_barang" // Mengambil jenis barang dari relasi
                            },
                            {
                                "data": "detail_barang.merek" // Merek barang
                            },
                            {
                                "data": "pc_number" // Nomor PC
                            },
                            {
                                "data": "detail_barang.status" // Status barang
                            },
                            {
                                "data": "detail_barang.kode_inventaris" // Kode inventaris barang
                            }
                        ],
                        // Menambahkan fitur filtering di setiap kolom
                        initComplete: function() {
                            this.api().columns().every(function() {
                                var column = this;
                                var select = $(
                                        '<select class="custom-select"><option value=""></option></select>'
                                    )
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );
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
