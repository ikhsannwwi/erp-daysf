<!-- Modal Detail User -->
<div class="modal fade" id="detailUser" tabindex="-1" aria-labelledby="detailUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailUserLabel">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailUserBody">

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
        $('#detailUser').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailUserBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.users.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;

                    // Assuming data.user_group is an object, you can check if it exists before rendering it.
                    var userGroupHtml = data.user_group ? data.user_group.name : '';

                    // Fungsi untuk mengubah angka bulan menjadi nama bulan
                    function formatBulan(angkaBulan) {
                        const namaBulan = [
                            "Januari", "Februari", "Maret", "April",
                            "Mei", "Juni", "Juli", "Agustus",
                            "September", "Oktober", "November", "Desember"
                        ];
                        return namaBulan[angkaBulan - 1] || "";
                    }

                    // Ubah format tanggal
                    var rawDate = data.profile ? (data.profile.tanggal_lahir ? data.profile.tanggal_lahir : '') : '';
                    var tanggal = new Date(rawDate).getDate();
                    var bulan = new Date(rawDate).getMonth() +
                        1; // Tambahkan 1 karena bulan dimulai dari 0
                    var tahun = new Date(rawDate).getFullYear();

                    var formattedDate = tanggal + " " + formatBulan(bulan) + " " + tahun;

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
                        '<div class="title">Nama</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.name + '</div>' +
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
                        '<div class="title">User Group</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + userGroupHtml + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Status</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + (data.status === '1' ? 'Aktif' : 'Tidak Aktif') +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );

                    if (data.profile) {
                        modalBody.append(
                            '<div class="row">' +
                            '<div class="col-5">' +
                            '<div class="title">Nama Lengkap</div>' +
                            '</div>' +
                            '<div class="col-7">' +
                            '<div class="data">: ' + (data.profile.full_name ? data.profile
                                .full_name : '') + '</div>' +
                            '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-5">' +
                            '<div class="title">No Telepon</div>' +
                            '</div>' +
                            '<div class="col-7">' +
                            '<div class="data">: ' + (data.profile.no_telepon ? data.profile
                                .no_telepon : '') + '</div>' +
                            '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-5">' +
                            '<div class="title">Pendidikan Terakhir</div>' +
                            '</div>' +
                            '<div class="col-7">' +
                            '<div class="data">: ' + (data.profile.pendidikan_terakhir ? data
                                .profile.pendidikan_terakhir : '') + '</div>' +
                            '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-5">' +
                            '<div class="title">Tempat, Tanggal Lahir</div>' +
                            '</div>' +
                            '<div class="col-7">' +
                            '<div class="data">: ' + (data.profile.tempat_lahir ? data.profile
                                .tempat_lahir : '') + ', ' + (formattedDate ? formattedDate : '') +
                            '</div>' +
                            '</div>' +
                            '</div>' +

                            '<div class="row">' +
                            '<div class="col-5">' +
                            '<div class="title">Alamat</div>' +
                            '</div>' +
                            '<div class="col-7">' +
                            '<div class="data">: ' + (data.profile.alamat ? data.profile.alamat :
                                '') + '</div>' +
                            '</div>' +
                            '</div>'
                        );
                    }



                    loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
                }
            });
        });
    </script>
@endpush
