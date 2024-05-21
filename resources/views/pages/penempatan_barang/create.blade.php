@extends('layouts.app')

@section('title', 'Tambah Penempatan Barang')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Penempatan Barang</h2>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Penempatan</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('penempatan_barang.store') }}" method="POST" id="penempatanForm">
                            @csrf
                            <div class="form-group">
                                <label for="id_ruangan">Ruangan</label>
                                <select class="form-control" id="id_ruangan" name="id_ruangan" required>
                                    @foreach($ruangan as $room)
                                        <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_penempatan">Tanggal Penempatan</label>
                                <input type="date" class="form-control" id="tanggal_penempatan" name="tanggal_penempatan" required>
                            </div>
                            <div id="dynamic-container">
                                <!-- Dynamic PC and Barang Card -->
                                <div class="card mb-3 pc-card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary">PC dan Barang</h6>
                                        <button type="button" class="btn btn-danger btn-sm remove-card">Hapus Card</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="pc_no">Nomor PC</label>
                                            <select class="form-control pc-no" name="pc_no[]" required>
                                                <option value="PC01">PC01</option>
                                                <option value="PC02">PC02</option>
                                                <option value="PC03">PC03</option>
                                                <!-- Tambahkan opsi PC lainnya sesuai kebutuhan -->
                                            </select>
                                        </div>
                                        <div class="dynamic-barang-container">
                                            <div class="barang-set">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="id_jenis_barang">Jenis Barang</label>
                                                        <select class="form-control jenis-barang" name="id_jenis_barang[]" required>
                                                            <option value="">Pilih Jenis Barang</option>
                                                            @foreach($jenisBarang as $jenis)
                                                                <option value="{{ $jenis->id }}">{{ $jenis->nama_barang }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="id_detail_barang">Detail Barang</label>
                                                        <select class="form-control detail-barang" name="id_detail_barang[]" required disabled>
                                                            <option value="">Pilih Detail Barang</option>
                                                            @foreach($detailBarang as $detail)
                                                                <option value="{{ $detail->id }}" data-jenis="{{ $detail->id_jenis_barang }}">
                                                                    {{ $detail->kode_inventaris }} - {{ $detail->merek }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm remove-barang">Hapus Barang</button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm add-barang">Tambah Barang</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mb-4" id="add-pc">Tambah PC</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function updateDetailBarangSelect(container) {
                container.find('.jenis-barang').on('change', function() {
                    var selectedJenis = $(this).val();
                    var detailSelect = $(this).closest('.barang-set').find('.detail-barang');
                    detailSelect.find('option').each(function() {
                        var optionJenis = $(this).data('jenis');
                        if (optionJenis == selectedJenis) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                    detailSelect.prop('disabled', selectedJenis === '');
                    detailSelect.val('');
                });
            }

            $('#add-pc').on('click', function() {
                var newPcCard = $('.pc-card:first').clone();
                newPcCard.find('select').val('');
                newPcCard.find('.dynamic-barang-container').html('<div class="barang-set">' + $('.barang-set:first').html() + '</div>');
                newPcCard.find('.remove-barang').removeClass('d-none');
                $('#dynamic-container').append(newPcCard);
                updateDetailBarangSelect(newPcCard);
            });

            $(document).on('click', '.remove-card', function() {
                if ($('.pc-card').length > 1) {
                    $(this).closest('.pc-card').remove();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Minimal harus ada satu PC!",
                    });
                }
            });

            $(document).on('click', '.add-barang', function() {
                var barangContainer = $(this).closest('.pc-card').find('.dynamic-barang-container');
                var newBarangSet = $('.barang-set:first').clone();
                newBarangSet.find('select').val('');
                newBarangSet.find('.remove-barang').removeClass('d-none');
                barangContainer.append(newBarangSet);
                updateDetailBarangSelect(newBarangSet);
            });

            $(document).on('click', '.remove-barang', function() {
                if ($(this).closest('.dynamic-barang-container').find('.barang-set').length > 1) {
                    $(this).closest('.barang-set').remove();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Barang pertama tidak dapat dihapus!",
                    });
                }
            });

            updateDetailBarangSelect($('.pc-card'));
        });
    </script>
@endsection
