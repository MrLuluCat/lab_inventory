@extends('layouts.app')

@section('title', 'Tambah Penempatan Barang')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Penempatan Barang Baru</h2>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Penempatan</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('penempatan_barang.store') }}" method="POST" id="penempatanForm">
                            @csrf
                            <!-- Input ruangan asal dan tujuan serta tanggal penempatan -->
                            <div class="form-group">
                                <label for="id_ruangan_asal">Ruangan Asal</label>
                                <select class="form-control" id="id_ruangan_asal" name="id_ruangan_asal" required>
                                    @foreach ($ruangan as $room)
                                        <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_ruangan_tujuan">Ruangan Tujuan</label>
                                <select class="form-control" id="id_ruangan_tujuan" name="id_ruangan_tujuan" required>
                                    @foreach ($ruangan as $room)
                                        <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_penempatan">Tanggal Penempatan</label>
                                <input type="date" class="form-control" id="tanggal_penempatan" name="tanggal_penempatan"
                                    required>
                            </div>

                            <!-- Scrollable Container for Dynamic PC and Barang Card -->
                            <div id="dynamic-container" class="d-flex overflow-auto"
                                style="gap: 16px; white-space: nowrap;">
                                <div class="card mb-3 pc-card d-inline-block" style="min-width: 100%;">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary">PC dan Barang</h6>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm add-barang mr-2">Tambah
                                                Barang</button>
                                            <button type="button" class="btn btn-danger btn-sm remove-card">Hapus
                                                PC</button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-group row d-flex justify-content-between align-items-center">
                                            <div class="col-md-2">
                                                <label for="pc_number" class="col-form-label">Nomor PC</label>
                                                <select class="form-control pc_number" name="pc_number[]" required>
                                                    <option value="PC01">PC01</option>
                                                    <option value="PC02">PC02</option>
                                                    <option value="PC03">PC03</option>
                                                    <option value="PC04">PC04</option>
                                                </select>
                                            </div>
                                            <div class="col-md-auto">

                                            </div>
                                        </div>

                                        <div class="dynamic-barang-container">
                                            <div class="barang-set">
                                                <div class="form-row mt-2 align-items-center">
                                                    <div class="form-group col-md-4">
                                                        <label for="id_jenis_barang">Jenis Barang</label>
                                                        <select class="form-control jenis-barang"
                                                            name="id_jenis_barang[0][]" required>
                                                            <option value="">Pilih Jenis Barang</option>
                                                            @foreach ($jenisBarang as $jenis)
                                                                <option value="{{ $jenis->id }}">
                                                                    {{ $jenis->nama_barang }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="id_detail_barang">Detail Barang</label>
                                                        <select class="form-control detail-barang"
                                                            name="id_detail_barang[0][]" required disabled>
                                                            <option value="">Pilih Detail Barang</option>
                                                            @if ($detailBarang->isEmpty())
                                                                <option value="" disabled>Stock Kosong</option>
                                                            @else
                                                                @foreach ($detailBarang as $detail)
                                                                    <option value="{{ $detail->id }}"
                                                                        data-jenis="{{ $detail->id_jenis_barang }}">
                                                                        MEREK: {{ $detail->merek }} - KODE:
                                                                        {{ $detail->kode_inventaris }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 d-flex justify-content-end align-items-center">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-barang mt-3 ml-1">Hapus
                                                            Barang</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">
                                <button type="button" class="btn btn-success mb-2 mx-1" id="add-pc">Tambah PC</button>
                                <button type="submit" class="btn btn-primary mb-2 mx-1">Simpan</button>
                            </div>
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
        var pcIndex = 0;

        function updateDetailBarangSelect(container) {
            container.find('.jenis-barang').on('change', function() {
                var selectedJenis = $(this).val();
                var detailSelect = $(this).closest('.barang-set').find('.detail-barang');

                detailSelect.empty();
                detailSelect.append('<option value="" disabled selected>Pilih Detail Barang</option>');
                detailSelect.prop('disabled', true);

                var matchingOptions = 0;
                @foreach ($detailBarang as $detail)
                    if ("{{ $detail->id_jenis_barang }}" == selectedJenis) {
                        detailSelect.append(
                            '<option value="{{ $detail->id }}">MEREK: {{ $detail->merek }} - KODE: {{ $detail->kode_inventaris }}</option>'
                        );
                        matchingOptions++;
                    }
                @endforeach

                if (matchingOptions === 0) {
                    detailSelect.append('<option value="" disabled selected>Stock Kosong</option>');
                } else {
                    detailSelect.prop('disabled', false);
                }
            });
        }

        // Function to validate items for each PC card
        function validateBarang() {
            let isValid = true;
            $('.pc-card').each(function() {
                const itemCounts = {};

                $(this).find('.jenis-barang').each(function() {
                    const selectedItemId = $(this).val(); // Get the id of selected jenis-barang
                    const itemType = $(this).find('option:selected').text().trim(); // Get the selected option text as itemType

                    if (selectedItemId) {
                        if (!itemCounts[itemType]) {
                            itemCounts[itemType] = 0;
                        }
                        itemCounts[itemType]++;
                    }
                });

                // Check item counts: no duplicates except for RAM, where maximum is 2
                for (const [itemType, count] of Object.entries(itemCounts)) {
                    if (itemType.includes('RAM') && count > 2) {
                        Swal.fire({
                            icon: "error",
                            title: "Validasi Gagal",
                            text: 'Maksimal 2 RAM diizinkan per PC.',
                            confirmButtonText: "OK"
                        });
                        isValid = false;
                        return false; // exit each loop
                    } else if (!itemType.includes('RAM') && count > 1) {
                        Swal.fire({
                            icon: "error",
                            title: "Validasi Gagal",
                            text: `Barang ${itemType} tidak boleh lebih dari satu per PC.`,
                            confirmButtonText: "OK"
                        });
                        isValid = false;
                        return false; // exit each loop
                    }
                }
            });
            return isValid;
        }

        // Prevent form submission if validation fails
        $('#penempatanForm').on('submit', function(e) {
            if (!validateBarang()) {
                e.preventDefault();
            }
        });

        $('#add-pc').on('click', function() {
            pcIndex++;
            var newPcCard = $('.pc-card:first').clone();
            newPcCard.find('select').val('');
            newPcCard.find('.dynamic-barang-container').html('<div class="barang-set">' + $('.barang-set:first').html() + '</div>');
            newPcCard.find('.jenis-barang').attr('name', 'id_jenis_barang[' + pcIndex + '][]');
            newPcCard.find('.detail-barang').attr('name', 'id_detail_barang[' + pcIndex + '][]');
            newPcCard.find('.remove-barang').removeClass('d-none');

            $('#dynamic-container').append(newPcCard);
            updateDetailBarangSelect(newPcCard);

            var container = $('#dynamic-container');
            container.animate({
                scrollLeft: container[0].scrollWidth
            }, 800);
        });

        $(document).on('click', '.remove-card', function() {
            if ($('.pc-card').length > 1) {
                $(this).closest('.pc-card').remove();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Minimal harus ada satu PC!"
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
                    text: "Barang pertama tidak dapat dihapus!"
                });
            }
        });

        updateDetailBarangSelect($('.pc-card'));
    });
</script>


@endsection
