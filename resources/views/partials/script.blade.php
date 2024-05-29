@yield('scripts')

<script>
        function confirmDelete(id) {
            // Swal.fire({
            //     title: 'Apakah Anda yakin?',
            //     text: "Data ini akan dihapus secara permanen!",
            //     icon: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#d33',
            //     confirmButtonText: 'Ya, hapus!'
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         document.getElementById('delete-form-' + id).submit();
            //     }
            // });

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mx-2',
                    cancelButton: 'btn btn-danger mx-2'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak, batalkan!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loader sebelum submit form
                    Swal.fire({
                        title: 'Menghapus data...',
                        text: 'Harap tunggu...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    document.getElementById('delete-form-' + id).submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Dibatalkan',
                        'Data Anda aman :)',
                        'info'
                    );
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}"
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: '{!! implode('<br>', $errors->all()) !!}'
            });
        </script>
    @endif

    