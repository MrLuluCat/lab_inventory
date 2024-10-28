@extends('layouts.app')

@section('title', 'Tambah Detail Barang')

@section('content')

    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Detail Barang</h2>
        <form id="barangForm" action="{{ route('detail_barang.store') }}" method="POST" novalidate>
            @csrf
            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    <div class="form-group">
                        <label for="no_surat">No Surat</label>
                        <select class="form-control" id="no_surat" name="no_surat" required>
                            @foreach ($suratMasuk as $surat)
                                <option value="{{ $surat->id }}">{{ $surat->no_surat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_jenis_barang">Jenis Barang</label>
                    </div>

                    <div class="form-group checkbox-container">
                        @foreach ($barang as $barang => $item)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                    id="barang{{ $item->id }}">
                                <label class="form-check-label" for="barang{{ $item->id }}">
                                    <i class="fa fa-check"></i> {{ $item->nama_barang }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div id="barangContainer"></div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <select class="form-control" id="lokasi" name="lokasi" required>
                            @foreach ($ruangan as $room)
                                <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Submit</button>
        </form>
    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('barangForm');
            const checkboxes = form.querySelectorAll('.form-check-input');
            const barangContainer = document.getElementById('barangContainer');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        addBarangInput(this.value, this.nextElementSibling.textContent.trim());
                    } else {
                        removeBarangInput(this.value);
                    }
                });
            });

            function addBarangInput(value, label) {
                const barangDiv = document.createElement('div');
                barangDiv.classList.add('card', 'mb-4', 'shadow-sm', 'barang-item');
                barangDiv.setAttribute('data-barang', value);

                barangDiv.innerHTML = `
                    <div class="card-body">
                        <h5>${label}</h5>
                        <input type="hidden" name="id_jenis_barang[${value}]" value="${value}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <input type="number" class="form-control" name="total[${value}]" placeholder="Kuantitas" required>
                                <div class="invalid-feedback">Harap masukkan kuantitas.</div>
                            </div>
                        </div>
                        <div class="kode-merek-container"></div>
                    </div>
                `;

                barangContainer.appendChild(barangDiv);

                const totalInput = barangDiv.querySelector('input[name="total[' + value + ']"]');
                totalInput.addEventListener('input', function() {
                    updateKodeMerek(barangDiv, label);
                });
            }

            function removeBarangInput(value) {
                const barangDiv = barangContainer.querySelector(`.barang-item[data-barang="${value}"]`);
                if (barangDiv) {
                    barangDiv.remove();
                }
            }

            function updateKodeMerek(barangDiv, label) {
                const total = barangDiv.querySelector('input[name="total[' + barangDiv.getAttribute(
                    'data-barang') + ']"]').value;
                let kodeMerekContainer = barangDiv.querySelector('.kode-merek-container');
                kodeMerekContainer.innerHTML = '';

                if (total) {
                    for (let i = 0; i < total; i++) {
                        const kodeMerekRow = document.createElement('div');
                        kodeMerekRow.classList.add('form-row', 'mt-2');

                        kodeMerekRow.innerHTML = `
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="kode_inventaris[${barangDiv.getAttribute('data-barang')}][${i}]" placeholder="Kode Inventaris ${i + 1}" required>
                                <div class="invalid-feedback">Harap masukkan kode inventaris.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="merek[${barangDiv.getAttribute('data-barang')}][${i}]" placeholder="Merek ${i + 1}" required>
                                <div class="invalid-feedback">Harap masukkan merek.</div>
                            </div>
                        `;

                        kodeMerekContainer.appendChild(kodeMerekRow);
                    }
                }
            }

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
@endsection
