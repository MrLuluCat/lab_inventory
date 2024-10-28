@extends('layouts.app')

@section('title', 'Daftar Ruangan')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Ruangan</h2>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Ruangan</h6>
                <a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-sm float-right">Tambah Ruangan</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Ruangan</th>
                                <th>Kapasitas</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruangan as $room)
                                <tr data-id="{{ $room->id }}">
                                    <td class="details-control"></td>
                                    <td>{{ $room->nama_ruangan }}</td>
                                    <td>{{ $room->kapasitas }}</td>
                                    <td>{{ $room->keterangan }}</td>
                                    <td>
                                        <a href="{{ route('ruangan.edit', $room->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $room->id }})">Hapus</button>
                                        <form id="delete-form-{{ $room->id }}"
                                            action="{{ route('ruangan.destroy', $room->id) }}" method="POST"
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
            // Fungsi format untuk menampilkan child row pertama (PC Number)
            function formatPcNumber(id) {
                var tableId = 'child-pc-table-' + id;
                return '<div class="child-table-wrapper">' + // Tambahkan wrapper untuk child table
                    '<table id="' + tableId + '" class="display child" style="width:100%">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Nomor PC</th>' +
                    '<th>Aksi</th>' +
                    '</tr>' +
                    '</thead>' +
                    '</table>' +
                    '</div>';
            }

            // Fungsi format untuk menampilkan child row kedua (Detail Barang per PC Number)
            function formatDetailBarang(pcNumber, idRuangan) {
                var tableId = 'child-detail-barang-' + pcNumber + '-' + idRuangan;
                return '<div class="child-table-wrapper">' + // Tambahkan wrapper untuk child table
                    '<table id="' + tableId + '" class="display child" style="width:100%">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Jenis Barang</th>' +
                    '<th>Merek</th>' +
                    '<th>Kode Inventaris</th>' +
                    '<th>Status</th>' +
                    '</tr>' +
                    '</thead>' +
                    '</table>' +
                    '</div>';
            }

            // Inisialisasi DataTable untuk tabel utama (Ruangan)
            var table = $('#dataTable').DataTable();

            // Event listener untuk menampilkan PC Number sebagai child row pertama
            $('#dataTable tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var rowId = tr.data('id'); // ID Ruangan

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(formatPcNumber(rowId)).show();
                    tr.addClass('shown');

                    // Inisialisasi DataTable untuk child row (PC Number)
                    var childTableId = 'child-pc-table-' + rowId;
                    if (!$.fn.DataTable.isDataTable('#' +
                        childTableId)) { // Cek apakah sudah diinisialisasi
                        $('#' + childTableId).DataTable({
                            ajax: {
                                url: '/api/child-ruangan/' + rowId,
                                dataSrc: 'data',
                            },
                            columns: [{
                                    "data": "pc_number"
                                }, // Menampilkan PC Number
                                {
                                    "data": null,
                                    "defaultContent": '<button class="btn btn-primary btn-sm pc-details-control">Detail</button>'
                                }
                            ],
                            drawCallback: function() {
                                // Bind event listener to dynamically generated "Detail" buttons
                                bindDetailButtonEvent(rowId);
                            }
                        });
                    }
                }
            });

            // Function to bind click event on dynamically created buttons for detail
            function bindDetailButtonEvent(rowId) {
                $(document).off('click', '.pc-details-control').on('click', '.pc-details-control', function() {
                    var pcRow = $(this).closest('tr');
                    var childTable = $('#' + pcRow.closest('table').attr('id')).DataTable();
                    var pcData = childTable.row(pcRow).data();
                    var pcNumber = pcData.pc_number;

                    if (childTable.row(pcRow).child.isShown()) {
                        childTable.row(pcRow).child.hide();
                        $(this).removeClass('shown');
                    } else {
                        childTable.row(pcRow).child(formatDetailBarang(pcNumber, rowId)).show();
                        $(this).addClass('shown');

                        // Inisialisasi DataTable untuk child row kedua (Detail Barang per PC)
                        var childDetailTableId = 'child-detail-barang-' + pcNumber + '-' + rowId;
                        if (!$.fn.DataTable.isDataTable('#' +
                            childDetailTableId)) { // Cek apakah sudah diinisialisasi
                            $('#' + childDetailTableId).DataTable({
                                ajax: {
                                    url: '/api/child-detail/' + rowId + '/' + pcNumber,
                                    dataSrc: 'data',
                                },
                                columns: [{
                                        "data": "detail_barang.barang.nama_barang"
                                    }, // Jenis Barang
                                    {
                                        "data": "detail_barang.merek"
                                    }, // Merek
                                    {
                                        "data": "detail_barang.kode_inventaris"
                                    }, // Kode Inventaris
                                    {
                                        "data": "detail_barang.status"
                                    } // Status
                                ]
                            });
                        }
                    }
                });
            }

            // Bind the detail button on page load for existing buttons
            bindDetailButtonEvent();
        });
    </script>


    {{-- <script>
        $(document).ready(function() {
            // Fungsi format untuk menampilkan child row pertama (PC Number)
            function formatPcNumber(id) {
                var tableId = 'child-pc-table-' + id;
                return '<table id="' + tableId + '" class="display" style="width:100%">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Nomor PC</th>' +
                    '<th>Aksi</th>' +
                    '</tr>' +
                    '</thead>' +
                    '</table>';
            }

            // Fungsi format untuk menampilkan child row kedua (Detail Barang per PC Number)
            function formatDetailBarang(pcNumber, idRuangan) {
                var tableId = 'child-detail-barang-' + pcNumber + '-' + idRuangan;
                return '<table id="' + tableId + '" class="display" style="width:100%">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Jenis Barang</th>' +
                    '<th>Merek</th>' +
                    '<th>Kode Inventaris</th>' +
                    '<th>Status</th>' +
                    '</tr>' +
                    '</thead>' +
                    '</table>';
            }

            // Inisialisasi DataTable untuk tabel utama (Ruangan)
            var table = $('#dataTable').DataTable();

            // Event listener untuk menampilkan PC Number sebagai child row pertama
            $('#dataTable tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var rowId = tr.data('id'); // ID Ruangan

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(formatPcNumber(rowId)).show();
                    tr.addClass('shown');

                    // Inisialisasi DataTable untuk child row (PC Number)
                    var childTableId = 'child-pc-table-' + rowId;
                    var childTable = $('#' + childTableId).DataTable({
                        ajax: {
                            url: '/api/child-ruangan/' + rowId,
                            dataSrc: 'data',
                        },
                        columns: [{
                                "data": "pc_number"
                            }, // Menampilkan PC Number
                            {
                                "data": null,
                                "defaultContent": ''
                            }
                        ],
                        drawCallback: function(settings) {
                            // Tampilkan detail barang setiap kali PC Number terbuka
                            $('#' + childTableId + ' tbody').on('click', 'tr', function() {
                                var pcRow = childTable.row(this);
                                var pcNumber = pcRow.data().pc_number;

                                if (pcRow.child.isShown()) {
                                    pcRow.child.hide();
                                    $(this).removeClass('shown');
                                } else {
                                    pcRow.child(formatDetailBarang(pcNumber, rowId))
                                        .show();
                                    $(this).addClass('shown');

                                    // Inisialisasi DataTable untuk child row kedua (Detail Barang per PC)
                                    var childDetailTableId = 'child-detail-barang-' +
                                        pcNumber + '-' + rowId;
                                    $('#' + childDetailTableId).DataTable({
                                        ajax: {
                                            url: '/api/child-detail/' + rowId +
                                                '/' + pcNumber,
                                            dataSrc: 'data',
                                        },
                                        columns: [{
                                                "data": "detail_barang.barang.nama_barang"
                                            }, // Jenis Barang
                                            {
                                                "data": "detail_barang.merek"
                                            }, // Merek
                                            {
                                                "data": "detail_barang.kode_inventaris"
                                            }, // Kode Inventaris
                                            {
                                                "data": "detail_barang.status"
                                            } // Status
                                        ]
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
    </script> --}}

@endsection
