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

                    modalBody.html(
                        '<p>ID: ' + data.id + '</p>' +
                        '<p>Nama: ' + data.name + '</p>' +
                        '<p>Email: ' + data.email + '</p>' +
                        '<p>User Group: ' + data.user_group.name + '</p>' +
                        '<p>Status: ' + (data.status === '1' ? 'Aktif' : 'Tidak Aktif') + '</p>'
                    );


                    loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
                }
            });
        });
    </script>
@endpush
