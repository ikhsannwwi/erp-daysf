<!-- Modal Detail Karyawan -->
<div class="modal fade" id="detailKaryawan" tabindex="-1" aria-labelledby="detailKaryawanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailKaryawanLabel">Detail Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailKaryawanBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $('#detailKaryawan').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailKaryawanBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.karyawan.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;

                    modalBody.html(
                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">ID</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.id + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Departemen</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.departemen.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Kode</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.kode + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Nama Depan</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.nama_depan + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Nama Belakang</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.nama_belakang + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Nama Lengkap</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Email</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.email + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Telepon</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.telepon + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Tanggal Lahir</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.tanggal_lahir + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Alamat</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.alamat + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Tanggal Bergabung</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.tanggal_bergabung + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Jabatan</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.jabatan + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Status</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + (data.status === 1 ? 'Aktif' : 'Tidak Aktif') +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );

                    loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
                }
            });
        });
    </script>
@endpush
